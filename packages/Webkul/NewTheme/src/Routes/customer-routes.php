<?php

use Illuminate\Support\Facades\Route;
use Webkul\NewTheme\Http\Controllers\Shop\Customer\Account\AddressController;

Route::group(['middleware' => ['web'], 'prefix' => 'shop/customer'], function () {
    /**
     * Addresses.
     */
    Route::controller(AddressController::class)->prefix('addresses')->group(function () {
        Route::post('create', 'store')->name('shop.customers.account.custom.addresses.store');
        Route::put('edit/{id}', 'update')->name('shop.customers.account.custom.addresses.update');
    });
});
