<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shop Theme Configuration
    |--------------------------------------------------------------------------
    |
    | All the configurations are related to the shop themes.
    |
    */

    'shop-default' => 'new-theme',

    'shop' => [
//        'default' => [
//            'name'        => 'Default',
//            'assets_path' => 'public/themes/shop/default',
//            'views_path'  => 'resources/themes/default/views',
//
//            'vite'        => [
//                'hot_file'                 => 'shop-default-vite.hot',
//                'build_directory'          => 'themes/shop/default/build',
//                'package_assets_directory' => 'src/Resources/assets',
//            ],
//        ],
        'new-theme' => [
            'name'        => 'Default',
            'assets_path' => 'public/themes/shop/new-theme',
            'views_path'  => 'resources/themes/new-theme/views',

            'vite'        => [
                'hot_file'                 => 'shop-new-theme-vite.hot',
                'build_directory'          => 'themes/shop/new-theme/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Theme Configuration
    |--------------------------------------------------------------------------
    |
    | All the configurations are related to the admin themes.
    |
    */

    'admin-default' => 'admin-theme',

    'admin' => [
        'default' => [
            'name'        => 'Default',
            'assets_path' => 'public/themes/admin/default',
            'views_path'  => 'resources/themes/default/views',

            'vite'        => [
                'hot_file'                 => 'admin-default-vite.hot',
                'build_directory'          => 'themes/admin/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
        'admin-theme' => [
            'name'        => 'Admin Theme',
            'assets_path' => 'public/themes/admin/new-admin-theme',
            'views_path'  => 'resources/themes/new-admin-theme/views',

            'vite'        => [
                'hot_file'                 => 'admin-new-admin-theme-vite.hot',
                'build_directory'          => 'themes/admin/new-admin-theme/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
    ],
];
