<?php

namespace Webkul\DeliveryAgents\GraphQL\Resolvers;

use Webkul\DeliveryAgents\Models\DeliveryAgentOrder;

class DeliveryAgentOrderResolver
{
    /**
     * Get can accept attribute
     */
    public function canAccept($rootValue, array $args, $context)
    {
        return $rootValue->status === DeliveryAgentOrder::STATUS_ASSIGNED_TO_AGENT;
    }

    /**
     * Get can reject attribute
     */
    public function canReject($rootValue, array $args, $context)
    {
        return $rootValue->status === DeliveryAgentOrder::STATUS_ASSIGNED_TO_AGENT;
    }

    /**
     * Get can complete attribute
     */
    public function canComplete($rootValue, array $args, $context)
    {
        return in_array($rootValue->status, [
            DeliveryAgentOrder::STATUS_ACCEPTED_BY_AGENT,
            DeliveryAgentOrder::STATUS_OUT_FOR_DELIVERY
        ]);
    }
}
