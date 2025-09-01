<?php

use Illuminate\Support\Facades\Route;
use Webkul\AdminTheme\Http\Controllers\Admin\Catalog\ProductController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/catalog'], function () {
    /**
     * Products routes.
     */
    Route::controller(ProductController::class)->prefix('product')->group(function () {
        Route::get('', 'index')->name('admin.catalog.custom.products.index');
    });

});
