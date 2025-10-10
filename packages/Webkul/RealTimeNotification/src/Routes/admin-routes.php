<?php

use Illuminate\Support\Facades\Route;
use Webkul\RealTimeNotification\Http\Controllers\Admin\RealTimeNotificationController;
use Webkul\RealTimeNotification\Http\Controllers\ServiceWorkerController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'realtimenotification'], function () {
    Route::get('firebase-config', [RealTimeNotificationController::class, 'getFirebaseConfig'])->name('shop.realtimenotification.firebase-config');
    Route::get('csrf-token', [RealTimeNotificationController::class, 'getCSRFToken'])->name('shop.realtimenotification.csrf-token');
    Route::post('save-token', [RealTimeNotificationController::class, 'saveToken'])->name('shop.realtimenotification.save-token');
    Route::post('subscribe-topic', [RealTimeNotificationController::class, 'subscribeToTopic'])->name('shop.realtimenotification.subscribe-topic');
    Route::get('subscription-status', [RealTimeNotificationController::class, 'getSubscriptionStatus'])->name('shop.realtimenotification.subscription-status');
});

// Service Worker route (must be at root level for FCM)
Route::get('firebase-messaging-sw.js', [ServiceWorkerController::class, 'generateServiceWorker'])
    ->name('firebase.messaging.sw');
