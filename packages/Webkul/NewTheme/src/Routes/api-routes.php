<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'api'], function () {


    Route::controller(CoreController::class)->prefix('core')->group(function () {


        Route::get('areas', 'getAreas')->name('shop.api.core.areas');

    });
});
