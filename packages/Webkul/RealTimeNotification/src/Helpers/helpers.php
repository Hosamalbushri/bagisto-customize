<?php

use Webkul\RealTimeNotification\Helpers\FirebaseHelper;

if (! function_exists('firebase_helper')) {
    function firebase_helper()
    {
        return app()->make(FirebaseHelper::class);
    }
}
