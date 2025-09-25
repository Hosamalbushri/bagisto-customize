<?php

namespace Webkul\AdminTheme\Provider;

use Illuminate\Support\ServiceProvider;
use Webkul\AdminTheme\Models\Address;
use Webkul\AdminTheme\Models\Country;
use Webkul\AdminTheme\Models\CountryState;
use Webkul\Core\Contracts\Address as AddressContract;
use Webkul\Core\Contracts\Country as CountryContract;
use Webkul\Core\Contracts\CountryState as CountryStateContract;

class AdminThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('Webkul\AdminTheme\Helpers\AdminHelper');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        include __DIR__.'/../Helpers/helpers.php';

        $this->publishes([
            __DIR__.'/../Resources/views' => resource_path('themes/new-admin-theme/views'),
        ]);
        $this->loadRoutesFrom(__DIR__.'/../Routes/catalog-routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/sales-routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/customers-routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/shop-routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/Country-routes.php');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'adminTheme');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'adminTheme');
        $this->registerConfig();
    }
    protected function registerModels(): void
    {
        $this->app->concord->registerModel(
            AddressContract::class,
            Address::class,
            CountryContract::class,
            Country::class,
            CountryStateContract::class,
            CountryState::class
        );
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/acl.php',
            'acl'
        );
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php',
            'core'
        );
    }
}
