<?php

namespace Webkul\DeliveryAgents\GraphQL\Queries\DeliveryAgent;

use Webkul\DeliveryAgents\Repositories\DeliveryAgentRepository;

class DeliveryAgentQuery
{
    protected $repository;

    public function __construct(DeliveryAgentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($rootValue, array $args)
    {
        return $this->repository->all();
    }

}
