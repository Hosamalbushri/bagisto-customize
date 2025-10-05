<?php

namespace Webkul\RealTimeNotification\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class RealTimeNotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
        include __DIR__.'/../Helpers/helpers.php';


        $this->app->singleton('Webkul\RealTimeNotification\Helpers\FirebaseHelper');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
//        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

//        $this->loadRoutesFrom(__DIR__.'/../Routes/admin-routes.php');

        $this->loadRoutesFrom(__DIR__.'/../Routes/shop-routes.php');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'realtimenotification');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'realtimenotification');

        Event::listen('bagisto.admin.layout.head.after', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('realtimenotification::admin.layouts.style');
        });
        Event::listen('bagisto.admin.layout.vue-app-mount.after', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('realtimenotification::admin.layouts.firebase-cdn');
        });
//        Event::listen('bagisto.admin.layout.vue-app-mount.after', function ($viewRenderEventManager) {
//            $viewRenderEventManager->addTemplate('realtimenotification::admin.layouts.admin-notifications');
//        });

        Event::listen('bagisto.shop.layout.head.after', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('realtimenotification::shop.layouts.style');
        });
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php',
            'core'
        );
    }
}
