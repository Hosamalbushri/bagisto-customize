<?php

namespace Webkul\DeliveryAgents\GraphQL\Helpers;

use Illuminate\Support\Facades\Validator;
use Webkul\GraphQLAPI\Validators\CustomException;

class DeliveryAgentGraphql
{
    public function __construct() {}

    /**
     * To validate the request data
     *
     * @return void
     */
    public function validate(array $args, array $rules, $messages = [])
    {
        $validator = Validator::make($args, $rules, $messages);

        $this->checkValidatorFails($validator);
    }

    /**
     * To check the validator fails
     *
     * @return void
     */
    public function checkValidatorFails($validator)
    {
        if ($validator->fails()) {
            $errorMessage = [];

            foreach ($validator->errors()->toArray() as $field => $messages) {
                foreach ($messages as $message) {
                    $errorMessage[] = "{$field}: {$message}";
                }
            }

            throw new CustomException(implode(', ', $errorMessage));
        }
    }

    public function authorize(string $guard = 'delivery-agent-api', ?string $token = null): mixed
    {
        if (! auth()->guard($guard)->check()) {
            throw new CustomException(trans('bagisto_graphql::app.shop.customers.no-login-customer'));
        }
        $deliveryAgent = auth()->guard($guard)->user();
        if (isset($deliveryAgent->status) && $deliveryAgent->status != 1) {
            $message = trans('deliveryAgent_graphql::app.auth.login.inactive');

        }
        if (isset($message)) {
            if ($token) {
                request()->merge(['token' => $token]);
            }

            auth()->guard($guard)->logout();

            throw new CustomException($message);
        }

        return $deliveryAgent;
    }
    public function getPaginatorInfo(object $collection): array
    {
        return [
            'count'       => $collection->count(),
            'currentPage' => $collection->currentPage(),
            'lastPage'    => $collection->lastPage(),
            'total'       => $collection->total(),
        ];
    }

    /**
     * Get validation messages for any field
     *
     * @param array $fields
     * @return array
     */
    public function getValidationMessages(array $fields = []): array
    {
        $messages = [];

        if (empty($fields)) {
            // Get all available validation fields
            $fields = [
                'first_name', 'last_name', 'gender', 'date_of_birth',
                'email', 'phone', 'password', 'new_password',
                'new_password_confirmation', 'current_password'
            ];
        }

        foreach ($fields as $field) {
            $fieldMessages = trans("deliveryAgent_graphql::app.validation.{$field}");
            if (is_array($fieldMessages)) {
                foreach ($fieldMessages as $rule => $message) {
                    $messages["{$field}.{$rule}"] = $message;
                }
            }
        }

        return $messages;
    }

    /**
     * Check if delivery agent can access order details
     *
     * @param int $orderId
     * @param int $deliveryAgentId
     * @return bool
     */
    public function canAccessOrder(int $orderId, int $deliveryAgentId): bool
    {
        $order = \Webkul\DeliveryAgents\Models\DeliveryAgentOrder::where('id', $orderId)
            ->where('delivery_agent_id', $deliveryAgentId)
            ->where('status', '!=', \Webkul\DeliveryAgents\Models\DeliveryAgentOrder::STATUS_REJECTED_BY_AGENT)
            ->first();

        return $order !== null;
    }

    /**
     * Get accessible orders for delivery agent
     *
     * @param int $deliveryAgentId
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getAccessibleOrders(int $deliveryAgentId, array $filters = [])
    {
        $query = \Webkul\DeliveryAgents\Models\DeliveryAgentOrder::where('delivery_agent_id', $deliveryAgentId)
            ->where('status', '!=', \Webkul\DeliveryAgents\Models\DeliveryAgentOrder::STATUS_REJECTED_BY_AGENT);

        // Apply additional filters
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        return $query;
    }
}
