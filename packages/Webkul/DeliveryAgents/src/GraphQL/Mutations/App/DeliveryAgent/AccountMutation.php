<?php

namespace Webkul\DeliveryAgents\GraphQL\Mutations\App\DeliveryAgent;

use App\Http\Controllers\Controller;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentRepository;
use Webkul\GraphQLAPI\Validators\CustomException;

class AccountMutation extends Controller
{
    /**
     * allowedImageMimeTypes array
     */
    protected array $allowedImageMimeTypes = [
        'bmp'  => 'image/bmp',
        'jpe'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg'  => 'image/jpeg',
        'png'  => 'image/png',
        'webp' => 'image/webp',
    ];

    public function __construct(
        protected DeliveryAgentRepository $deliveryAgentRepository
    ) {
        Auth::setDefaultDriver('delivery-agent-api');
    }

    /**
     * Update the authenticated delivery agent profile.
     *
     * @throws CustomException
     */
    public function update(mixed $rootValue, array $args, GraphQLContext $context): array
    {
        $agent = delivery_graphql()->authorize();

        if (! $agent) {
            throw new CustomException(trans('deliveryAgent_graphql::app.account.unauthenticated'));
        }

        delivery_graphql()->validate($args, [
            'first_name'                => 'required|string',
            'last_name'                 => 'required|string',
            'gender'                    => 'required|in:Other,Male,Female',
            'date_of_birth'             => 'nullable|date|before:today',
            'email'                     => 'required|email|unique:delivery_agents,email,'.$agent->id,
            'new_password'              => 'confirmed|min:6|required_with:current_password',
            'new_password_confirmation' => 'required_with:new_password',
            'current_password'          => 'required_with:new_password',
            'phone'                     => ['required', 'unique:delivery_agents,phone,'.$agent->id, new PhoneNumber],
        ], delivery_graphql()->getValidationMessages());
        $isPasswordChanged = false;

        try {
            // Normalize date
            $args['date_of_birth'] = ! empty($args['date_of_birth'])
                ? Carbon::createFromTimeString(str_replace('/', '-', $args['date_of_birth']).' 00:00:01')->format('Y-m-d')
                : null;

            // Handle password change
            if (! empty($args['current_password'])) {
                if (Hash::check($args['current_password'], $agent->password)) {
                    $isPasswordChanged = true;
                    $args['password'] = bcrypt($args['new_password']);
                } else {
                    throw new CustomException(trans('deliveryAgent_graphql::app.account.password-unmatch'));
                }
            } else {
                unset($args['new_password'], $args['new_password_confirmation'], $args['current_password']);
            }

            Event::dispatch('deliveryAgent.update.before', $agent->id);

            // Update model
            $updated = $this->deliveryAgentRepository->update($args, $agent->id);
            $agent->refresh();

            // Handle image upload/delete
            if (array_key_exists('image', $args)) {
                $file = $args['image'] ?? null;

                if ($file instanceof UploadedFile) {
                    // Validate MIME
                    $mime = $file->getMimeType();
                    if (! in_array($mime, array_values($this->allowedImageMimeTypes), true)) {
                        throw new CustomException(trans('deliveryAgent_graphql::app.account.image-invalid'));
                    }

                    if ($agent->image) {
                        Storage::delete($agent->image);
                    }

                    $agent->image = $file->store("deliveryAgent/{$agent->id}");
                    $agent->save();
                } elseif (is_null($file)) {
                    // Remove image
                    if ($agent->image) {
                        Storage::delete($agent->image);
                    }
                    $agent->image = null;
                    $agent->save();
                }
            }

            if ($isPasswordChanged) {
                Event::dispatch('deliveryAgent.update.password', $agent);
            }

            Event::dispatch('deliveryAgent.update.after', $agent);

            return [
                'success'       => true,
                'message'       => trans('deliveryAgent_graphql::app.account.update-success'),
                'deliveryAgent' => $agent,
            ];
        } catch (\Exception $e) {
            throw new CustomException($e->getMessage());
        }
    }

    /**
     * Delete the authenticated delivery agent account.
     *
     * @throws CustomException
     */
    public function delete(mixed $rootValue, array $args, GraphQLContext $context): array
    {
        $agent = delivery_graphql()->authorize();
        delivery_graphql()->validate($args, [
            'password' => 'required',
        ], delivery_graphql()->getValidationMessages());
        try {
            if (! Hash::check($args['password'], $agent->password)) {
                return [
                    'success' => false,
                    'message' => trans('deliveryAgent_graphql::app.account.wrong-password'),
                ];
            }

            // Optional: add domain-specific checks before delete if needed

            $deleted = $this->deliveryAgentRepository->deleteIfNoIncompleteOrders($agent->id);

            if (! $deleted) {
                return [
                    'success' => false,
                    'message' => trans('deliveryAgent_graphql::app.account.delete-failed'),

                ];
            }

            return [
                'success' => true,
                'message' => trans('deliveryAgent_graphql::app.account.delete-success'),
            ];
        } catch (\Exception $e) {
            throw new CustomException($e->getMessage());
        }
    }

}
