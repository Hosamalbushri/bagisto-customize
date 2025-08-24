<?php

namespace Webkul\DeliveryAgents\Repositories;

use Webkul\Core\Eloquent\Repository;

class DeliveryAgentOrders extends Repository
{

    public function model()
    {
        return '\Webkul\DeliveryAgents\Models\DeliveryAgentOrder';
    }
}
