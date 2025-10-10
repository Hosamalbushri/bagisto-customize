<?php

namespace Webkul\RealTimeNotification\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\RealTimeNotification\Listeners\AdminLoginListener;

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

        $this->loadRoutesFrom(__DIR__.'/../Routes/admin-routes.php');


        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'realtimenotification');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'realtimenotification');

        Event::listen('bagisto.admin.layout.head.after', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('realtimenotification::admin.layouts.style');
        });
        Event::listen('bagisto.admin.layout.vue-app-mount.after', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('realtimenotification::admin.layouts.firebase-cdn');
        });

        // Listen for admin login events
        Event::listen('admin.login.after', AdminLoginListener::class);

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
