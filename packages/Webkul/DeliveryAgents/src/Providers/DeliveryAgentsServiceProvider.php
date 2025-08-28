<?php

namespace Webkul\DeliveryAgents\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

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

            $viewRenderEventManager->addTemplate('deliveryagents::admin.Orders.index');
        });
        Event::listen('bagisto.admin.sales.order.Invoice.after', function ($viewRenderEventManager) {

            $viewRenderEventManager->addTemplate('deliveryagents::admin.Orders.view');
        });
        Event::listen('sales.shipment.save.after', 'Webkul\DeliveryAgents\Listeners\UpdateInOrderFields@afterSaveShipment');
        Event::listen('sales.order.cancel.before', 'Webkul\DeliveryAgents\Listeners\UpdateInOrderFields@beforeCancelOrder');
        Event::listen('sales.refund.save.after', 'Webkul\DeliveryAgents\Listeners\UpdateInOrderFields@afterSaveRefund');
        $this->app->concord->registerModel(
            \Webkul\Sales\Contracts\Order::class,
            \Webkul\DeliveryAgents\Models\Order::class,
            \Webkul\DeliveryAgents\Models\Address::class
        );

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/DeliveryAgent-routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/Order-routes.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/Country-routes.php');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'deliveryagent');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'deliveryagents');

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php', 'core'
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
            dirname(__DIR__).'/Config/menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/acl.php', 'acl'
        );

    }
}
