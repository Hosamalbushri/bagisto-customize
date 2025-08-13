<?php

namespace Webkul\DeliveryAgents\Repositories;

use Webkul\Core\Eloquent\Repository;

class AreaRepository extends Repository
{

    public function model()
    {
        return '\Webkul\DeliveryAgents\Models\Areas';
    }
}
