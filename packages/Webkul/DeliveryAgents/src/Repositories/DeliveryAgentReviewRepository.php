<?php

namespace Webkul\DeliveryAgents\Repositories;

use Webkul\Core\Eloquent\Repository;

class DeliveryAgentReviewRepository extends Repository
{

    public function model()
    {
        return '\Webkul\DeliveryAgents\Models\DeliveryAgentReview';
    }
}
