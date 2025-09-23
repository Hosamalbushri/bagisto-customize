<?php

namespace Webkul\AdminTheme\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Areas extends Model
{
    protected $table = 'state_areas';

    protected $fillable = [
        'area_name',
        'country_state_id',
        'state_code',
        'country_code',
    ];
    public function countrystate(): BelongsTo
    {
        return $this->belongsTo(CountryState::class, 'country_state_id');
    }

    public function ranges(): HasMany
    {
        return $this->hasMany(\Webkul\DeliveryAgents\Models\Range::class, 'state_area_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'state_area_id');
    }
}

