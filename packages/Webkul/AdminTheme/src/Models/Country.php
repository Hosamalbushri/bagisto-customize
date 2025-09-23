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
        return $this->hasMany(CountryState::class, 'country_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CountryTranslation::class, 'country_id');
    }
}

