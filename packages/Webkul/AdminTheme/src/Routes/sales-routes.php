<?php

use Illuminate\Support\Facades\Route;
use Webkul\AdminTheme\Http\Controllers\Admin\Sales\CartController;
use Webkul\AdminTheme\Http\Controllers\Admin\Sales\OrderController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/sales/order'], function () {
    /**
     * Orders routes.
     */
    Route::controller(OrderController::class)->prefix('order')->group(function () {
        Route::get('', 'index')->name('admin.sales.custom.orders.index');
    });

    Route::controller(CartController::class)->prefix('cart')->group(function () {
        Route::post('{id}/addresses', 'storeAddress')->name('admin.sales.cart.custom.addresses.store');

    });

});
