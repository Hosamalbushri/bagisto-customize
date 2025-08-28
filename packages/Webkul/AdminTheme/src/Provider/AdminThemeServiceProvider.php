<?php

namespace Webkul\AdminTheme\Provider;

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

    }
}
