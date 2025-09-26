<?php

namespace Webkul\AdminTheme\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Webkul\AdminTheme\Models\Address;
use Webkul\AdminTheme\Models\Country;
use Webkul\AdminTheme\Models\CountryState;
use Webkul\Core\Contracts\Address as AddressContract;
use Webkul\Core\Contracts\CountryStateTranslation as CountryStateTranslationContract;
use Webkul\Core\Contracts\CountryTranslation as CountryContract;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        CountryContract::class                 => Country::class,
        CountryStateTranslationContract::class => CountryState::class,
        AddressContract::class                 => Address::class,
    ];
}
