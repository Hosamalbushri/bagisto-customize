<?php

namespace Webkul\DeliveryAgents\Repositories;

use Webkul\Core\Eloquent\Repository;

class StateRepository extends Repository
{

    public function model()
    {
        return 'Webkul\DeliveryAgents\Models\State';
    }
}
