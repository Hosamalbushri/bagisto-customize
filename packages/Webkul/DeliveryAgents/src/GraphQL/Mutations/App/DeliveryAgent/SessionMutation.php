<?php

namespace Webkul\DeliveryAgents\GraphQL\Mutations\App\DeliveryAgent;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Webkul\GraphQLAPI\Validators\CustomException;

class SessionMutation extends Controller
{
    /**
     * Use the delivery agent guard by default.
     */
    public function __construct()
    {
        Auth::setDefaultDriver('delivery-agent-api');
    }

    /**
     * Login delivery agent and return token payload.
     *
     * @throws CustomException
     */
    public function login(mixed $rootValue, array $args, GraphQLContext $context): array
    {
        // Validate inputs with translated messages (email OR phone required)
        $validator = Validator::make($args, [
            'email'    => 'nullable|email',
            'phone'    => 'nullable|string',
            'password' => 'required|string|min:6',
        ], [
            'email.email'        => trans('deliveryAgent::validation.login.email.email'),
            'password.required'  => trans('deliveryAgent::validation.login.password.required'),
            'password.min'       => trans('deliveryAgent::validation.login.password.min'),
        ]);

        $validator->after(function ($v) use ($args) {
            if (empty($args['email']) && empty($args['phone'])) {
                $v->errors()->add('identifier', trans('deliveryAgent::validation.login.identifier.required'));
            }
        });

        if ($validator->fails()) {
            throw new CustomException($validator->errors()->first());
        }

        // Build credentials based on provided identifier
        $credentials = ['password' => $args['password']];

        if (! empty($args['phone'])) {
            $credentials['phone'] = $args['phone'];
        } else {
            $credentials['email'] = $args['email'];
        }

        // Attempt auth using delivery-agent-api guard
        if (! $jwtToken = JWTAuth::attempt($credentials, $args['remember'] ?? 0)) {
            throw new CustomException(trans('deliveryAgent::auth.login.invalid-creds'));
        }

        try {
            // Retrieve the authenticated delivery agent
            $deliveryAgent = Auth::user();

            // If account is not active, invalidate token and block login
            if (! $this->isActive($deliveryAgent)) {
                // Invalidate the just-issued token
                try {
                    JWTAuth::invalidate($jwtToken);
                } catch (\Throwable $t) {
                    // ignore invalidate failures
                }

                throw new CustomException(trans('deliveryAgent::auth.login.inactive'));
            }

            // Dispatch an event hook if you want to listen to it
            Event::dispatch('deliveryAgent.after.login', $deliveryAgent);

            return [
                'success'        => true,
                'message'        => trans('deliveryAgent::auth.login.success'),
                // expose both keys for compatibility
                'access_token'   => $jwtToken,
                'token'          => $jwtToken,
                'token_type'     => 'Bearer',
                'expires_in'     => Auth::factory()->getTTL() * 60,
                'deliveryAgent'  => $deliveryAgent,
            ];
        } catch (\Exception $e) {
            throw new CustomException($e->getMessage());
        }
    }

    /**
     * Determine if a delivery agent account is active.
     */
    protected function isActive($agent): bool
    {
        if (! $agent) {
            return false;
        }

        $status = $agent->status;

        if (is_bool($status)) {
            return $status === true;
        }

        if (is_numeric($status)) {
            return (int) $status === 1;
        }

        if (is_string($status)) {
            $value = strtolower(trim($status));
            return $value === 'active' || $value === '1' || $value === 'enabled';
        }

        return false;
    }

    /**
     * Logout the authenticated delivery agent.
     */
    public function logout(): array
    {
        $deliveryAgent = Auth::user();

        Auth::logout();

        Event::dispatch('deliveryAgent.after.logout', $deliveryAgent?->id);

        return [
            'success' => true,
            'message' => trans('deliveryAgent::auth.logout.success'),
        ];
    }
}
