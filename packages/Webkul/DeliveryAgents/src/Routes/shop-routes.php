<?php

use Illuminate\Support\Facades\Route;
use Webkul\DeliveryAgents\Http\Controllers\Shop\DeliveryAgentsController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'deliveryagents'], function () {
    Route::get('', [DeliveryAgentsController::class, 'index'])->name('shop.deliveryagents.index');
});
