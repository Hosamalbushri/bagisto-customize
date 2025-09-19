<?php

namespace App\Contracts;

/**
 * Order Status Interface
 * 
 * This interface defines the order status constants that can be used
 * across different packages without direct dependency on DeliveryAgents.
 */
interface OrderStatusInterface
{
    // Standard order statuses
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELED = 'canceled';
    const STATUS_CLOSED = 'closed';
    const STATUS_PENDING_PAYMENT = 'pending_payment';
    const STATUS_FRAUD = 'fraud';

    // Delivery agent related statuses
    const STATUS_ASSIGNED_TO_AGENT = 'assigned_to_agent';
    const STATUS_ACCEPTED_BY_AGENT = 'accepted_by_agent';
    const STATUS_REJECTED_BY_AGENT = 'rejected_by_agent';
    const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';

    /**
     * Get all delivery agent related statuses
     *
     * @return array
     */
    public static function getDeliveryAgentStatuses(): array;

    /**
     * Check if status is delivery agent related
     *
     * @param string $status
     * @return bool
     */
    public static function isDeliveryAgentStatus(string $status): bool;

    /**
     * Get status label
     *
     * @param string $status
     * @return string
     */
    public static function getStatusLabel(string $status): string;
}
