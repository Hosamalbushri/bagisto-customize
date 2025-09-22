<?php

namespace Webkul\AdminTheme\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Core\Models\CountryTranslation;

class Country extends CountryTranslation
{
    protected $table = 'countries';

    protected $fillable = [
        'name',
        'code',
    ];

    public function states(): HasMany
    {
        return $this->hasMany(State::class, 'country_id');
    }
}

