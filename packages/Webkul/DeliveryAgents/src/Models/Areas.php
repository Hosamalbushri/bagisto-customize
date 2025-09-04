<?php

namespace Webkul\DeliveryAgents\Models;

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
        return $this->hasMany(Range::class, 'state_area_id');
    }
}
