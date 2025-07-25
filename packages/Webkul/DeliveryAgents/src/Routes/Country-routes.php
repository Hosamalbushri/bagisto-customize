<?php

use Illuminate\Support\Facades\Route;
use Webkul\DeliveryAgents\Http\Controllers\Admin\CountriesController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/countries'], function () {
    Route::controller(CountriesController::class)->group(function () {
        Route::get('', [CountriesController::class, 'index'])->name('admin.country.index');
        Route::get('/create', [CountriesController::class, 'create'])->name('admin.country.create');
        Route::post('', [CountriesController::class, 'store'])->name('admin.country.store');
        Route::get('/view/{id}', [CountriesController::class, 'show'])->name('admin.country.view');
        Route::post('/update/{id}', [CountriesController::class, 'update'])->name('admin.country.update');
        Route::delete('/delete/{id}', [CountriesController::class, 'destroy'])->name('admin.country.delete');

    });
});

