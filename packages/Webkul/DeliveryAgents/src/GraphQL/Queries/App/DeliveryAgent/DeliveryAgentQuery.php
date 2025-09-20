<?php

namespace Webkul\DeliveryAgents\GraphQL\Queries\App\DeliveryAgent;

use App\Http\Controllers\Controller;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class DeliveryAgentQuery extends Controller
{
    /**
     * Get the authenticated delivery agent account information.
     */
    public function get(mixed $rootValue, array $args, GraphQLContext $context): mixed
    {
        return delivery_graphql()->authorize();
    }
}
