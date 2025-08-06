<?php

namespace Webkul\DeliveryAgents\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Sales\Models\Order as BaseModel;

class Order extends BaseModel
{
    public function delivery_agent(): BelongsTo
    {
        return $this->belongsTo(DeliveryAgent::class, 'delivery_agent_id');
    }
}
