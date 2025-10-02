<?php

use Illuminate\Support\Facades\Route;
use Webkul\RealTimeNotification\Http\Controllers\Shop\RealTimeNotificationController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'realtimenotification'], function () {
    Route::get('', [RealTimeNotificationController::class, 'index'])->name('shop.realtimenotification.index');
    Route::get('firebase-config', [RealTimeNotificationController::class, 'getFirebaseConfig'])->name('shop.realtimenotification.firebase-config');
    Route::post('save-token', [RealTimeNotificationController::class, 'saveToken'])->name('shop.realtimenotification.save-token');
});
