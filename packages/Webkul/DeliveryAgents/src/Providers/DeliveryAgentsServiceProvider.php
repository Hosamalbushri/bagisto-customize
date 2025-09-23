<?php

namespace Webkul\DeliveryAgents\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\DeliveryAgents\Listeners\UpdateInOrderFields;
use Webkul\DeliveryAgents\Models\Address;
use Webkul\DeliveryAgents\Models\Order;
use Webkul\Sales\Contracts\Order as OrderContract;

/**
 * Delivery Agents Service Provider
 *
 * This service provider handles the registration and bootstrapping
 * of the Delivery Agents package functionality.
 */
class DeliveryAgentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerEventListeners();
        $this->registerModels();
        $this->loadPackageResources();
        $this->registerConfig();

    }

    /**
     * Register services.
     */
    public function register(): void
    {}

    /**
     * Register event listeners for the package.
     */
    protected function registerEventListeners(): void
    {
        // Admin layout events
        Event::listen('bagisto.admin.layout.head.after', function ($view) {
            $view->addTemplate('DeliveryAgents::admin.layouts.style');
        });

        // Order pages events
        Event::listen('bagisto.admin.sales.order.page_action.before', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('DeliveryAgents::admin.DeliveryAgents.Orders.index');
        });
        Event::listen('bagisto.admin.sales.order.Invoice.after', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('DeliveryAgents::admin.DeliveryAgents.Orders.view');
        });
        // Review pages events
        Event::listen('bagisto.admin.customers.reviews.index.add_tab', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('DeliveryAgents::admin.DeliveryAgents.DeliveryAgent.reviews.index');
        });
        // shop customer events
        Event::listen('bagisto.shop.customers.account.orders.view.after.Shipping.tab', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('DeliveryAgents::shop.Customer.Orders.view');
        });
        // Sales events
        Event::listen('sales.shipment.save.after', UpdateInOrderFields::class.'@afterSaveShipment');
        Event::listen('sales.order.cancel.before', UpdateInOrderFields::class.'@beforeCancelOrder');
        Event::listen('sales.refund.save.after', UpdateInOrderFields::class.'@afterSaveRefund');
        // Commented out invoice event - uncomment if needed
        // Event::listen('sales.invoice.save.after', UpdateInOrderFields::class . '@afterSaveInvoice');
    }

    /**
     * Register model bindings.
     */
    protected function registerModels(): void
    {
        $this->app->concord->registerModel(
            OrderContract::class,
            Order::class,
        );
    }

    /**
     * Load package resources (migrations, routes, views, translations).
     */
    protected function loadPackageResources(): void
    {
        include __DIR__.'/../GraphQL/Helpers/helpers.php';

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../Routes/DeliveryAgent-routes.php');
        // Load translations
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'deliveryAgent');
        $this->loadTranslationsFrom(__DIR__.'/../GraphQL/Resources/lang', 'deliveryAgent_graphql');
        // Load views
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'DeliveryAgents');
    }

    /**
     * Register package configuration files.
     */
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

        // Merge Delivery Agents auth guard and provider
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/auth/guards.php',
            'auth.guards'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/auth/providers.php',
            'auth.providers'
        );
    }
}
