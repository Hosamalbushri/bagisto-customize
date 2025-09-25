<?php

use Illuminate\Support\Facades\Route;
use Webkul\AdminTheme\Http\Controllers\Admin\Customers\AddressController;
use Webkul\AdminTheme\Http\Controllers\Admin\Customers\CustomerController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/customer'], function () {
    /**
     * customer routes.
     */
    Route::controller(CustomerController::class)->prefix('customer')->group(function () {
        Route::get('', 'index')->name('admin.customers.custom.customers.index');

        Route::get('view/{id}', 'show')->name('admin.customers.custom.customers.view');
    });
    /**
     * Customer's addresses routes.
     */
    Route::controller(AddressController::class)->group(function () {
        Route::prefix('{id}/addresses')->group(function () {
            Route::post('create', 'store')->name('admin.customers.customers.custom.addresses.store');
        });

        Route::prefix('addresses')->group(function () {
            Route::put('edit/{id}', 'update')->name('admin.customers.customers.custom.addresses.update');
        });
    });

});
