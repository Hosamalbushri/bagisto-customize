<?php

namespace Webkul\DeliveryAgents\Repositories;

use Webkul\Core\Eloquent\Repository;

class RangeRepository extends Repository
{

    public function model()
    {
        return 'Webkul\DeliveryAgents\Models\Range';
    }
}
