<?php

namespace Webkul\NewTheme\Provider;

use Illuminate\Support\ServiceProvider;

class NewThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../Resources/views' => resource_path('themes/new-theme/views'),
        ]);
        $this->loadRoutesFrom(__DIR__.'/../Routes/api-routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/customer-routes.php');
    }
}
