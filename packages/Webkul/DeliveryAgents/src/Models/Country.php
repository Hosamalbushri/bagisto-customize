<?php

namespace Webkul\DeliveryAgents\Models;

use Webkul\Core\Models\CountryTranslation;

class Country extends CountryTranslation
{
    protected $table = 'countries';

    protected $fillable = [
        'name',
        'code',
    ];




}
