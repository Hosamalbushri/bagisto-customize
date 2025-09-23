<?php
if (! function_exists('delivery_graphql')) {
    function delivery_graphql()
    {
        return app()->make(AdminHelper::class);
    }
}
