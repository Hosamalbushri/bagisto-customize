<?php

namespace Webkul\DeliveryAgents\Models;

use Illuminate\Database\Eloquent\Model;

class Range extends Model
{
    protected $table = 'delivery_agent_ranges';
    protected $fillable = ['delivery_agent_id','state_area_id','created_at','updated_at'];

    public function deliveryAgents()
    {
        return $this->belongsTo(DeliveryAgent::class);

    }
    public function stateArea()
    {
        return $this->belongsTo(Areas::class);

    }



}
