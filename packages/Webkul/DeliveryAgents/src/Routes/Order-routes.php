<?php

use Illuminate\Support\Facades\Route;
use Webkul\DeliveryAgents\Http\Controllers\Admin\Orders\OrdersController;
use Webkul\DeliveryAgents\Http\Controllers\Shop\ShopOrderController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/sales'], function () {
    Route::controller(OrdersController::class)->prefix('order')->group(function () {
        Route::get('', 'index')->name('admin.sales.order.index');

    });

});

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'customer'], function () {
    Route::controller(ShopOrderController::class)->prefix('order')->group(function () {
        Route::get('', 'index')->name('shop.customers.account.order.index');

    });

});
