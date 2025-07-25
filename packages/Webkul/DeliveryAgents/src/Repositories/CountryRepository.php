<?php

namespace Webkul\DeliveryAgents\Repositories;
use Webkul\Core\Eloquent\Repository;

class CountryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return '\Webkul\DeliveryAgents\Models\Country';
    }





}
