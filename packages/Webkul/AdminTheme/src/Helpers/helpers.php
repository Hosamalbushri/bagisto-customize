<?php

use Webkul\AdminTheme\Helpers\AdminHelper;

if (! function_exists('admin_helper')) {
    function admin_helper()
    {
        return app()->make(AdminHelper::class);
    }
}
