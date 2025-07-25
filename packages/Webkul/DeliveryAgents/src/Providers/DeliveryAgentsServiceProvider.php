<?php

namespace Webkul\DeliveryAgents\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class DeliveryAgentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('bagisto.admin.layout.head.after', function ($view) {
            $view->addTemplate('deliveryagents::admin.layouts.style');
        });
        Event::listen('bagisto.admin.sales.order.page_action.before', function ($viewRenderEventManager) {

            $viewRenderEventManager->addTemplate('deliveryagents::admin.Orders.selected-delivery-agent');
        });

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/DeliveryAgent-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/Country-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/State-routes.php');


        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'deliveryagent');


        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'deliveryagents');

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );

    }
}
