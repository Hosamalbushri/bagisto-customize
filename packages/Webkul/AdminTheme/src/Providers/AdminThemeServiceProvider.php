<?php

namespace Webkul\AdminTheme\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\AdminTheme\Listeners\OrderStatusUpdateListener;
use Webkul\AdminTheme\Listeners\OrderSaveListener;

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

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->registerConfig();
        $this->registerEventListeners();
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

    /**
     * Register event listeners.
     */
    protected function registerEventListeners(): void
    {
        Event::listen('sales.order.update-status.after', OrderStatusUpdateListener::class);
        Event::listen('checkout.order.save.after', OrderSaveListener::class);
    }
}
