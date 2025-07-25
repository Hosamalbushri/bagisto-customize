<?php

namespace Webkul\DeliveryAgents\Models;

use Illuminate\Database\Eloquent\Model;

class Range extends Model
{
    protected $table = 'delivery_agent_ranges';
    protected $fillable = ['area_name','country','state','created_at','updated_at'];

    public function deliveryAgents()
    {
        return $this->belongsTo(DeliveryAgent::class);

    }



}
