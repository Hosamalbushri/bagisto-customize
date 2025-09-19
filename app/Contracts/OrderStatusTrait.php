<?php

namespace App\Contracts;

/**
 * Order Status Trait
 * 
 * This trait provides implementation for OrderStatusInterface
 * and can be used by any class that needs order status functionality.
 */
trait OrderStatusTrait
{
    /**
     * Get all delivery agent related statuses
     *
     * @return array
     */
    public static function getDeliveryAgentStatuses(): array
    {
        return [
            self::STATUS_ASSIGNED_TO_AGENT,
            self::STATUS_ACCEPTED_BY_AGENT,
            self::STATUS_REJECTED_BY_AGENT,
            self::STATUS_OUT_FOR_DELIVERY,
        ];
    }

    /**
     * Check if status is delivery agent related
     *
     * @param string $status
     * @return bool
     */
    public static function isDeliveryAgentStatus(string $status): bool
    {
        return in_array($status, self::getDeliveryAgentStatuses());
    }

    /**
     * Get status label
     *
     * @param string $status
     * @return string
     */
    public static function getStatusLabel(string $status): string
    {
        $labels = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELED => 'Canceled',
            self::STATUS_CLOSED => 'Closed',
            self::STATUS_PENDING_PAYMENT => 'Pending Payment',
            self::STATUS_FRAUD => 'Fraud',
            self::STATUS_ASSIGNED_TO_AGENT => 'Assigned to Agent',
            self::STATUS_ACCEPTED_BY_AGENT => 'Accepted by Agent',
            self::STATUS_REJECTED_BY_AGENT => 'Rejected by Agent',
            self::STATUS_OUT_FOR_DELIVERY => 'Out for Delivery',
        ];

        return $labels[$status] ?? $status;
    }
}
