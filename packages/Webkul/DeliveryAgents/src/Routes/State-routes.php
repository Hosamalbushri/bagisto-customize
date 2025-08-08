<?php

use Illuminate\Support\Facades\Route;
use Webkul\DeliveryAgents\Http\Controllers\Admin\StatesController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/states'], function () {
    Route::controller(StatesController::class)->group(function () {
        Route::get('', [StatesController::class, 'index'])->name('admin.states.index');
        Route::post('create', [StatesController::class, 'store'])->name('admin.states.store');
        Route::get('edit/{id}', [StatesController::class, 'edit'])->name('admin.states.edit');
        Route::put('edit/{id}', [StatesController::class, 'update'])->name('admin.states.update');
        Route::delete('edit/{id}', [StatesController::class, 'delete'])->name('admin.states.delete');
        Route::post('mass-delete', [StatesController::class, 'massDelete'])->name('admin.states.mass_delete');


    });
});
