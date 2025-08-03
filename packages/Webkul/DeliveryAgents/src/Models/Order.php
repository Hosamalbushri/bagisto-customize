<?php

namespace Webkul\DeliveryAgents\Models;

use Webkul\DeliveryAgents\Contracts\Order as OrderContract;
use Webkul\Sales\Models\Order as BaseModel;

class Order extends BaseModel implements OrderContract
{
    protected $table = 'orders';

    public function hasDeliveryAgent(): bool
    {
        return ! empty($this->delivery_agent_id);
    }
}
