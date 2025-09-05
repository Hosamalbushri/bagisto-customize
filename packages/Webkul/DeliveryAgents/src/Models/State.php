<?php

namespace Webkul\DeliveryAgents\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Core\Models\CountryStateTranslation;

class State extends CountryStateTranslation
{
    protected $table = 'country_states';

    protected $fillable = ['country_id', 'country_code', 'default_name', 'code'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function areas(): HasMany
    {
        return $this->hasMany(Areas::class, 'country_state_id');

    }
}
