<?php

namespace Webkul\DeliveryAgents\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Sales\Models\Order as BaseModel;

class Order extends BaseModel
{
    public const STATUS_PENDING = 'pending';

    /**
     * Payment pending state.
     */
    public const STATUS_PENDING_PAYMENT = 'pending_payment';

    /**
     * Processing state.
     */
    public const STATUS_PROCESSING = 'processing';

    /**
     * Completed state.
     */
    public const STATUS_COMPLETED = 'completed';

    /**
     * Canceled state.
     */
    public const STATUS_CANCELED = 'canceled';

    /**
     * Closed state.
     */
    public const STATUS_CLOSED = 'closed';

    /**
     * Fraud state.
     */
    public const STATUS_FRAUD = 'fraud';

    /**
     * Assigned state.
     */
    const STATUS_ASSIGNED_TO_AGENT = 'assigned_to_agent';

    /**
     * Accepted state.
     */
    const STATUS_ACCEPTED_BY_AGENT = 'accepted_by_agent';

    /**
     * Rejected state.
     */
    const STATUS_REJECTED_BY_AGENT = 'rejected_by_agent';

    /**
     * Out For Delivery state.
     */
    const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';

    /**
     * Delivered state.
     */
    const STATUS_DELIVERED = 'delivered';

    protected $statusLabelKeys = [
        self::STATUS_PENDING              => 'deliveryagent::app.orders.status.pending',
        self::STATUS_PENDING_PAYMENT      => 'deliveryagent::app.orders.status.pending_payment',
        self::STATUS_PROCESSING           => 'deliveryagent::app.orders.status.processing',
        self::STATUS_COMPLETED            => 'deliveryagent::app.orders.status.completed',
        self::STATUS_CANCELED             => 'deliveryagent::app.orders.status.canceled',
        self::STATUS_CLOSED               => 'deliveryagent::app.orders.status.closed',
        self::STATUS_FRAUD                => 'deliveryagent::app.orders.status.fraud',
        self::STATUS_ASSIGNED_TO_AGENT    => 'deliveryagent::app.orders.status.assigned_to_agent',
        self::STATUS_ACCEPTED_BY_AGENT    => 'deliveryagent::app.orders.status.accepted_by_agent',
        self::STATUS_REJECTED_BY_AGENT    => 'deliveryagent::app.orders.status.rejected_by_agent',
        self::STATUS_OUT_FOR_DELIVERY     => 'deliveryagent::app.orders.status.out_for_delivery',
        self::STATUS_DELIVERED            => 'deliveryagent::app.orders.status.delivered',
    ];

    public function getStatusLabelAttribute(): string
    {
        $key = $this->statusLabelKeys[$this->status] ?? null;

        return $key ? __($key) : (string) $this->status;
    }

    public function deliveryAgent(): BelongsTo
    {
        return $this->belongsTo(DeliveryAgent::class, 'delivery_agent_id');
    }

    public function deliveryAssignments(): HasMany
    {
        return $this->hasMany(DeliveryAgentOrder::class);
    }

    public function canDelivery(): bool
    {
        foreach ($this->items as $item) {
            if (
                $item->canShip()
                && ! in_array($item->order->status, [
                    self::STATUS_CLOSED,
                    self::STATUS_FRAUD,
                ]) && ! in_array($item->order->delivery_status, [
                    self::STATUS_ASSIGNED_TO_AGENT,
                    self::STATUS_ACCEPTED_BY_AGENT,
                    self::STATUS_OUT_FOR_DELIVERY,
                    self::STATUS_DELIVERED,
                ])
            ) {
                return true;
            }
        }

        return false;
    }

    public function notVisible(): bool
    {
        if (empty($this->delivery_status)) {
            return true;
        }
        if (! $this->canShip()) {
            return true;
        }

        if (in_array($this->status, [
            self::STATUS_CLOSED,
            self::STATUS_FRAUD,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELED,

        ])) {
            return true;
        }

        return false;
    }

    public function isRejected(): bool
    {
        if ($this->delivery_status == self::STATUS_REJECTED_BY_AGENT) {
            return true;
        }

        return false;
    }

    public function getIsDeliveredAttribute(): bool
    {
        return $this->delivery_status == self::STATUS_DELIVERED;
    }
}
