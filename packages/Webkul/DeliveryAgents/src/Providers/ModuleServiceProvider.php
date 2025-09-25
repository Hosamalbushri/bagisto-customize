<?php

namespace Webkul\DeliveryAgents\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Webkul\DeliveryAgents\Models\Order;
use Webkul\Sales\Contracts\Order as OrderContract;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        OrderContract::class => Order::class,
    ];
}
