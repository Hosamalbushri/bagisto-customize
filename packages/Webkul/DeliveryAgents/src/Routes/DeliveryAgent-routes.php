<?php

use Illuminate\Support\Facades\Route;
use Webkul\DeliveryAgents\Http\Controllers\Admin\DeliveryAgentsController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/delivery-agents'], function () {
    Route::controller(DeliveryAgentsController::class)->group(function () {
        Route::get('', 'index')->name('admin.deliveryagents.index');
        Route::get('/create', [DeliveryAgentsController::class, 'create'])->name('admin.deliveryagents.create');
        Route::post('', [DeliveryAgentsController::class, 'store'])->name('admin.deliveryagents.store');
        Route::get('/view/{id}', [DeliveryAgentsController::class, 'show'])->name('admin.deliveryagents.view');
        Route::post('/update/{id}', [DeliveryAgentsController::class, 'update'])->name('admin.deliveryagents.update');
        Route::delete('/delete/{id}', [DeliveryAgentsController::class, 'destroy'])->name('admin.deliveryagents.delete');

        //************ Routs for Ranges Of Deliver Agentes ************************
        Route::post('/range/add', [DeliveryAgentsController::class, 'storeRange'])->name('admin.range.store');
        Route::post('/update/range/{id}', [DeliveryAgentsController::class, 'updataRange'])->name('admin.range.update');





    });
});
