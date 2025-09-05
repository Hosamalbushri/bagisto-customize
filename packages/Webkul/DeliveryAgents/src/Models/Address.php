<?php

namespace Webkul\DeliveryAgents\Models;

use Webkul\Core\Models\Address as BaseModel;

class Address extends BaseModel
{
    protected $fillable = ['state_area_id'];
}
