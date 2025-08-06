<?php

use Illuminate\Support\Facades\Route;
use Webkul\DeliveryAgents\Http\Controllers\Admin\CountriesController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/countries'], function () {
    Route::controller(CountriesController::class)->group(function () {
        Route::get('', [CountriesController::class, 'index'])->name('admin.country.index');
        Route::post('create', [CountriesController::class, 'store'])->name('admin.country.store');
        Route::get('edit/{id}', [CountriesController::class, 'edit'])->name('admin.country.edit');
        Route::put('edit/{id}', [CountriesController::class, 'update'])->name('admin.country.update');
        Route::delete('edit/{id}', [CountriesController::class, 'delete'])->name('admin.country.delete');
        Route::post('mass-delete', [CountriesController::class, 'massDelete'])->name('admin.country.mass_delete');

    });
});

