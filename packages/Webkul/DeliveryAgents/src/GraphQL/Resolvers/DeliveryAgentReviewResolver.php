<?php

namespace Webkul\DeliveryAgents\GraphQL\Resolvers;

use Webkul\DeliveryAgents\Models\DeliveryAgentReview;

class DeliveryAgentReviewResolver
{
    /**
     * Get status label attribute
     */
    public function statusLabel($rootValue, array $args, $context)
    {
        $statusLabels = [
            DeliveryAgentReview::STATUS_PENDING => __('deliveryAgent::app.reviews.status.pending'),
            DeliveryAgentReview::STATUS_APPROVED => __('deliveryAgent::app.reviews.status.approved'),
            DeliveryAgentReview::STATUS_DISAPPROVED => __('deliveryAgent::app.reviews.status.disapproved'),
        ];

        return $statusLabels[$rootValue->status] ?? $rootValue->status;
    }
}
