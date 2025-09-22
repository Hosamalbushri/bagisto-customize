<?php

namespace Webkul\DeliveryAgents\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\AdminTheme\Models\Areas;

class Range extends Model
{
    protected $table = 'delivery_agent_ranges';

    protected $fillable = ['delivery_agent_id', 'state_area_id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at', 'updated_at'];

    public function deliveryAgents()
    {
        return $this->belongsTo(DeliveryAgent::class);

    }

    public function state_area()
    {
        return $this->belongsTo(Areas::class)->select(['id', 'state_code', 'country_code', 'country_state_id', 'area_name']);

    }
}
