<?php

namespace Webkul\AdminTheme\Models;

use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    protected $table = 'state_areas';

    protected $fillable = [
        'area_name',
        'country_state_id',
        'state_code',
        'country_code',
    ];

    public function ranges()
    {
        return $this->hasMany(\Webkul\DeliveryAgents\Models\Range::class, 'state_area_id');
    }
    
    public function addresses()
    {
        return $this->hasMany(\Webkul\DeliveryAgents\Models\Address::class, 'state_area_id');
    }
}

