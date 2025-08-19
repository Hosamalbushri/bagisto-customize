<?php

use App\Helpers\Helper;

if (! function_exists('myHelper')) {
    function myHelper(): Helper
    {
        return app(Helper::class);
    }
}
