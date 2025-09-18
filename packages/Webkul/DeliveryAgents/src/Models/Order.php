<?php

namespace Webkul\DeliveryAgents\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        self::STATUS_PENDING              => 'deliveryAgent::app.orders.status.pending',
        self::STATUS_PENDING_PAYMENT      => 'deliveryAgent::app.orders.status.pending_payment',
        self::STATUS_PROCESSING           => 'deliveryAgent::app.orders.status.processing',
        self::STATUS_COMPLETED            => 'deliveryAgent::app.orders.status.completed',
        self::STATUS_CANCELED             => 'deliveryAgent::app.orders.status.canceled',
        self::STATUS_CLOSED               => 'deliveryAgent::app.orders.status.closed',
        self::STATUS_FRAUD                => 'deliveryAgent::app.orders.status.fraud',
        self::STATUS_ASSIGNED_TO_AGENT    => 'deliveryAgent::app.orders.status.assigned_to_agent',
        self::STATUS_ACCEPTED_BY_AGENT    => 'deliveryAgent::app.orders.status.accepted_by_agent',
        self::STATUS_REJECTED_BY_AGENT    => 'deliveryAgent::app.orders.status.rejected_by_agent',
        self::STATUS_OUT_FOR_DELIVERY     => 'deliveryAgent::app.orders.status.out_for_delivery',
        self::STATUS_DELIVERED            => 'deliveryAgent::app.orders.status.delivered',
    ];

    protected $casts = [
        'is_delivered' => 'boolean',
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

    public function deliveredDeliveryAssignment(): HasOne
    {
        return $this->hasOne(DeliveryAgentOrder::class)
            ->where('status', DeliveryAgentOrder::STATUS_DELIVERED)
            ->latestOfMany('completed_at');
    }

    public function deliveryAgentReview(): HasOne
    {
        return $this->hasOne(DeliveryAgentReview::class);
    }

    public function canDelivery(): bool
    {
        // If there is already a delivered assignment, this order cannot be delivered again.
        $hasDeliveredAssignment = $this->relationLoaded('deliveredDeliveryAssignment')
            ? (bool) $this->deliveredDeliveryAssignment
            : $this->deliveredDeliveryAssignment()->exists();

        if ($hasDeliveredAssignment) {
            return false;
        }
        foreach ($this->items as $item) {
            if (
                $item->canShip()
                && ! in_array($item->order->status, [
                    self::STATUS_CLOSED,
                    self::STATUS_FRAUD,
                ]) && empty($this->delivery_agent_id)
            ) {
                return true;
            }
        }

        return false;
    }

    public function isRejected(): bool
    {
        if ($this->status == self::STATUS_REJECTED_BY_AGENT) {
            return true;
        }

        return false;
    }

    public function getIsDeliveredAttribute(): bool
    {
        return (bool) $this->attributes['is_delivered'];
    }

    public function showDeliveryTab(): bool
    {
        if ($this->status == self::STATUS_OUT_FOR_DELIVERY ) {
            return true;
        }
        if ($this->status == self::STATUS_COMPLETED ) {
            return true;
        }

        return false;
    }

    /**
     * التحقق من وجود مراجعة للطلب
     */
    public function hasReview(): bool
    {
        return $this->deliveryAgentReview()->exists();
    }

    /**
     * الحصول على المراجعة إذا كانت موجودة
     */
    public function getReview()
    {
        return $this->deliveryAgentReview;
    }
}
