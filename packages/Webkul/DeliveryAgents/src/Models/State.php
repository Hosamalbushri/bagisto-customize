<?php

namespace Webkul\DeliveryAgents\Models;

use Webkul\Core\Models\CountryStateTranslation;

class State extends CountryStateTranslation
{
    protected $table = 'country_states';
    protected $fillable = ['country_id','country_code','default_name','code'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }



}
