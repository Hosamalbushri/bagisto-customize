<?php

use Illuminate\Support\Facades\Route;
use Webkul\AdminTheme\Http\Controllers\Shop\Customer\OrderController;

Route::group(['middleware' => ['web', 'shop'], 'prefix' => 'customer'], function () {
    Route::controller(OrderController::class)->prefix('order')->group(function () {
        Route::get('', 'index')->name('shop.customers.account.order.index');

    });
});
