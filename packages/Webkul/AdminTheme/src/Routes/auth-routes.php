<?php

use Illuminate\Support\Facades\Route;
use Webkul\AdminTheme\Http\Controllers\Admin\User\SessionController;
use Webkul\AdminTheme\Http\Controllers\Controller;

Route::group(['prefix' => config('app.admin_url')], function () {
    /**
     * Redirect route.
     */
    Route::get('/', [Controller::class, 'redirectToLogin']);

    Route::controller(SessionController::class)->prefix('auth/login')->group(function () {
        /**
         * Login routes.
         */
        Route::get('', 'create')->name('admin.session.custom.create');
        /**
         * Login post route to admin auth controller.
         */
        Route::post('', 'store')->name('admin.session.custom.store');

    });
});
