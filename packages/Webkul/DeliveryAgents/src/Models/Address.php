<?php

namespace Webkul\DeliveryAgents\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Contracts\CustomerAddress as CustomerAddressContract;

use Webkul\Core\Models\Address  as BaseModel;
use Webkul\Sales\Contracts\OrderAddress as OrderAddressContract;

class Address extends BaseModel
{
    protected $fillable =['state_area_id'];

}
