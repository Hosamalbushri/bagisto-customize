<?php

namespace Webkul\DeliveryAgents\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryAgentOrder extends Model
{
    protected $table = 'delivery_agent_orders';

    const STATUS_ASSIGNED_TO_AGENT = 'assigned_to_agent';

    const STATUS_ACCEPTED_BY_AGENT = 'accepted_by_agent';

    const STATUS_REJECTED_BY_AGENT = 'rejected_by_agent';

    const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';

    const STATUS_DELIVERED = 'delivered';

    protected $fillable = [
        'order_id',
        'delivery_agent_id',
        'delivery_agent_info',
        'status',
        'assigned_at',
        'completed_at',
        'rejected_at',
        'accepted_at',
    ];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryAgent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DeliveryAgent::class);
    }

    protected $statusLabelKeys = [
        self::STATUS_ASSIGNED_TO_AGENT    => 'deliveryAgent::app.orders.status.assigned_to_agent',
        self::STATUS_ACCEPTED_BY_AGENT    => 'deliveryAgent::app.orders.status.accepted_by_agent',
        self::STATUS_REJECTED_BY_AGENT    => 'deliveryAgent::app.orders.status.rejected_by_agent',
        self::STATUS_OUT_FOR_DELIVERY     => 'deliveryAgent::app.orders.status.out_for_delivery',
        self::STATUS_DELIVERED            => 'deliveryAgent::app.orders.status.delivered',
    ];

    public function getStatusLabelAttribute(): string
    {
        $key = $this->statusLabelKeys[$this->status] ?? null;

        return $key ? __($key) : (string) $this->status;
    }

    public function isRejected(): bool
    {
        if ($this->status == self::STATUS_REJECTED_BY_AGENT) {
            return true;
        }

        return false;
    }
}
