<?php

use Illuminate\Support\Facades\Route;
use Webkul\DeliveryAgents\Http\Controllers\Admin\StatesController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/states'], function () {
    Route::controller(StatesController::class)->group(function () {
        Route::get('', [StatesController::class, 'index'])->name('admin.states.index');
//        Route::get('/create', [StatesController::class, 'create'])->name('admin.country.create');
        Route::post('', [StatesController::class, 'store'])->name('admin.states.store');
        Route::get('/view/{id}', [StatesController::class, 'show'])->name('admin.states.view');
        Route::post('/update/{id}', [StatesController::class, 'update'])->name('admin.states.update');
        Route::delete('/delete/{id}', [StatesController::class, 'destroy'])->name('admin.states.delete');

    });
});
