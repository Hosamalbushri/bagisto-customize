<?php

namespace Webkul\AdminTheme\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Core\Models\CountryState;

class State extends CountryState
{
    protected $table = 'country_states';
    public $translatedAttributes = ['default_name'];

    protected $with = ['translations'];

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

