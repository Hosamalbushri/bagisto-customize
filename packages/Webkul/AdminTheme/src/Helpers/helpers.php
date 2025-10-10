<?php

use Webkul\AdminTheme\Helpers\AdminHelper;
use Webkul\AdminTheme\Helpers\FirebaseHelper;

if (! function_exists('admin_helper')) {
    function admin_helper()
    {
        return app()->make(AdminHelper::class);
    }
}
if (! function_exists('auth_firebase_helper')) {
    function auth_firebase_helper()
    {
        return app()->make(FirebaseHelper::class);
    }
}
