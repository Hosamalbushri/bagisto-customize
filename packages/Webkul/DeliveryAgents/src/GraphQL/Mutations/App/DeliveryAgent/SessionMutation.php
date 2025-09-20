<?php

namespace Webkul\DeliveryAgents\GraphQL\Mutations\App\DeliveryAgent;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
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
        delivery_graphql()->validate($args, [
            'email'    => 'nullable|email',
            'phone'    => 'nullable|string',
            'password' => 'required|string|min:6',
        ], delivery_graphql()->getValidationMessages());
        if (empty($args['email']) && empty($args['phone'])) {
            throw new CustomException(trans('deliveryAgent_graphql::app.auth.login.email-or-phone-required'));
        }
        $credentials = ['password' => $args['password']];
        if (! empty($args['email'])) {
            $credentials['email'] = $args['email'];
        } else {
            $credentials['phone'] = $args['phone'];
        }

        if (! $jwtToken = JWTAuth::attempt($credentials, $args['remember'] ?? 0)) {
            throw new CustomException(trans('deliveryAgent_graphql::app.auth.login.invalid-creds'));
        }

        try {
            // Retrieve the authenticated delivery agent
            $deliveryAgent = delivery_graphql()->authorize(token: $jwtToken);
            $deliveryAgent->device_token = $args['device_token'] ?? null;
            $deliveryAgent->save();
            // Dispatch an event hook if you want to listen to it
            Event::dispatch('deliveryAgent.after.login', $deliveryAgent);
            return [
                'success'        => true,
                'message'        => trans('deliveryAgent_graphql::app.auth.login.success'),
                'access_token'   => $jwtToken,
                'token_type'     => 'Bearer',
                'expires_in'     => Auth::factory()->getTTL() * 60,
                'deliveryAgent'  => $deliveryAgent,
            ];
        } catch (\Exception $e) {
            throw new CustomException($e->getMessage());
        }
    }

    /**
     * Logout the authenticated delivery agent.
     */
    public function logout(): array
    {
        $deliveryAgent = delivery_graphql()->authorize();
        auth()->logout();
        Auth::logout();

        Event::dispatch('deliveryAgent.after.logout', $deliveryAgent?->id);

        return [
            'success' => true,
            'message' => trans('deliveryAgent_graphql::app.auth.logout.success'),
        ];
    }
}
