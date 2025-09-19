<?php

namespace Webkul\AdminTheme\Provider;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AdminThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../Resources/views' => resource_path('themes/new-admin-theme/views'),
        ]);
        $this->loadRoutesFrom(__DIR__.'/../Routes/catalog-routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/sales-routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/customers-routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/shop-routes.php');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'adminTheme');
        $this->registerConfig();
        Event::listen('area.before.delete', 'Webkul\AdminTheme\Listeners\PreventDeleteIfHasChildren@beforeDeleteArea');
    }
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php',
            'core'
        );
    }

}
