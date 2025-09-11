<?php

use Illuminate\Support\Facades\Route;
use Webkul\DeliveryAgents\Http\Controllers\Admin\Country\AreasController;
use Webkul\DeliveryAgents\Http\Controllers\Admin\Country\CountriesController;
use Webkul\DeliveryAgents\Http\Controllers\Admin\Country\StatesController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/country'], function () {
    /**
     * country routes.
     */
    Route::controller(CountriesController::class)->prefix('')->group(function () {
        Route::get('', 'index')->name('admin.country.index');
        Route::post('create', 'store')->name('admin.country.store');
        Route::get('edit/{id}', 'edit')->name('admin.country.edit');
        Route::put('edit/{id}', 'update')->name('admin.country.update');
        Route::delete('edit/{id}', 'delete')->name('admin.country.delete');
        Route::post('mass-delete', 'massDelete')->name('admin.country.mass_delete');

    });
    /**
     * state  routes.
     */
    Route::controller(StatesController::class)->prefix('state')->group(function () {
        Route::get('', 'index')->name('admin.states.index');
        Route::post('create', 'store')->name('admin.states.store');
        Route::get('edit/{id}', 'edit')->name('admin.states.edit');
        Route::put('edit/{id}', 'update')->name('admin.states.update');
        Route::delete('edit/{id}', 'delete')->name('admin.states.delete');
        Route::post('mass-delete', 'massDelete')->name('admin.states.mass_delete');

    });

    /**
     * area state routes.
     */
    Route::controller(AreasController::class)->prefix('area')->group(function () {
        Route::get('', 'index')->name('admin.area.index');
        Route::post('create', 'store')->name('admin.area.store');
        Route::get('view/{id}', 'view')->name('admin.area.view');
        Route::get('edit/{id}', 'edit')->name('admin.area.edit');
        Route::put('edit', 'update')->name('admin.area.update');
        Route::delete('edit/{id}', 'delete')->name('admin.area.delete');

    });

});
