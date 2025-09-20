<?php

use Webkul\DeliveryAgents\GraphQL\Helpers\DeliveryAgentGraphql;

if (! function_exists('delivery_graphql')) {
    function delivery_graphql()
    {
        return app()->make(DeliveryAgentGraphql::class);
    }
}
