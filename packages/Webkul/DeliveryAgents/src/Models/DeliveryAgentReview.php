<?php

namespace Webkul\DeliveryAgents\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryAgentReview extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DISAPPROVED = 'disapproved';

    protected $table = 'delivery_agent_reviews';
    protected $fillable = [
        'comment',
        'rating',
        'status',
        'delivery_agent_id',
        'order_id',
        'customer_id',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function deliveryAgent()
    {
        return $this->belongsTo(DeliveryAgent::class);
    }

}
