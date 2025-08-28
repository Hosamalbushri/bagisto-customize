<?php

use Webkul\AdminTheme\Http\Controllers\Admin\Sales\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/sales/order'], function () {
    /**
     * Orders routes.
     */
    Route::controller(OrderController::class)->prefix('order')->group(function () {
        Route::get('', 'index')->name('admin.sales.custom.orders.index');
    });

});
