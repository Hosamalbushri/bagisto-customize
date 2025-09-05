<?php

use Illuminate\Support\Facades\Route;
use Webkul\DeliveryAgents\Http\Controllers\Admin\DeliveryAgents\DeliveryAgentsController;
use Webkul\DeliveryAgents\Http\Controllers\Admin\DeliveryAgents\RangesController;
use Webkul\DeliveryAgents\Http\Controllers\Admin\Orders\OrdersController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/delivery'], function () {
    /**
     * delivery routes.
     */
    Route::controller(DeliveryAgentsController::class)->prefix('agents')->group(function () {
        Route::get('', 'index')->name('admin.deliveryAgents.index');
        Route::get('create', 'create')->name('admin.deliveryAgents.create');
        Route::post('', 'store')->name('admin.deliveryAgents.store');
        Route::get('select-delivery-agent', 'selectedDeliveryAgents')->name('admin.deliveryAgents.order.select-delivery-agent');
        Route::get('view/{id}', 'view')->name('admin.deliveryAgents.view');
        Route::put('edit/{id}', 'update')->name('admin.deliveryAgents.update');
        Route::delete('edit/{id}', 'destroy')->name('admin.deliveryAgents.delete');
        Route::post('mass-delete', 'massDestroy')->name('admin.deliveryAgents.mass_delete');
        Route::post('mass-update', 'massUpdate')->name('admin.deliveryAgents.mass_update');

    });
    /**
     * delivery Ranges routes.
     */
    Route::controller(RangesController::class)->prefix('range')->group(function () {
        Route::post('create', 'store')->name('admin.range.store');
        Route::put('update/{id}', 'update')->name('admin.range.update');
        Route::post('delete/{id}', 'delete')->name('admin.range.delete');

    });

    /**
     * delivery Order routes.
     */
    Route::controller(OrdersController::class)->prefix('agents/orders')->group(function () {
        Route::post('/assign-to-delivery-agent/{order}/{agent}', 'assignToAgent')->name('admin.orders.assignDeliveryAgent');
        Route::post('edit/{id}', 'changeStatus')->name('admin.orders.changeStatus');


    });


});

