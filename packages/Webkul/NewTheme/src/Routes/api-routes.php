<?php
use Illuminate\Support\Facades\Route;
use Webkul\NewTheme\Http\Controllers\Shop\Api\AddressController;
use Webkul\NewTheme\Http\Controllers\Shop\Api\OnepageController;
use Webkul\NewTheme\Http\Controllers\Shop\CoreController;

Route::group(['middleware' => ['web'], 'prefix' => 'shop/api'], function () {
    Route::controller(CoreController::class)->prefix('core')->group(function () {
        Route::get('areas', 'getAreas')->name('shop.api.core.areas');

    });
    Route::group(['middleware' => ['customer'], 'prefix' => 'customer'], function () {
        Route::controller(AddressController::class)->prefix('addresses')->group(function () {

            Route::post('', 'store')->name('shop.api.customers.account.custom.addresses.store');

            Route::put('edit/{id?}', 'update')->name('shop.api.customers.account.custom.addresses.update');
        });
    });
    Route::controller(OnepageController::class)->prefix('checkout/onepage')->group(function () {
        Route::post('addresses', 'storeAddress')->name('shop.checkout.onepage.custom.addresses.store');
    });

});
