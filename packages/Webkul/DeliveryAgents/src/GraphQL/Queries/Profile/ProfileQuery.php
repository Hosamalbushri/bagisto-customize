<?php

namespace Webkul\DeliveryAgents\GraphQL\Queries\Profile;

use Illuminate\Auth\AuthenticationException;

class ProfileQuery
{
    /**
     * Return the authenticated delivery agent.
     */
    public function me($rootValue, array $args)
    {
        $user = auth('delivery-agent-api')->user();

        if (! $user) {
            throw new AuthenticationException('Unauthenticated.');
        }

        return $user;
    }
}
