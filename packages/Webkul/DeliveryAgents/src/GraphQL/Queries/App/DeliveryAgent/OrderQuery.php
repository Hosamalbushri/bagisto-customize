<?php

namespace Webkul\DeliveryAgents\GraphQL\Queries\App\DeliveryAgent;

use Illuminate\Support\Facades\DB;
use Webkul\DeliveryAgents\Models\DeliveryAgentOrder;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentOrders;

class OrderQuery
{
    /**
     * @var DeliveryAgentOrders
     */
    protected $deliveryAgentOrdersRepository;

    public function __construct(
        DeliveryAgentOrders $deliveryAgentOrdersRepository
    ) {
        $this->deliveryAgentOrdersRepository = $deliveryAgentOrdersRepository;
    }

    /**
     * Get delivery agent orders
     */
    public function orders($rootValue, array $args, $context)
    {
        $deliveryAgent = delivery_graphql()->authorize();


        $query = delivery_graphql()->getAccessibleOrders($deliveryAgent->id, $args)
            ->with(['order', 'deliveryAgent']);

        // Apply ordering
        $orderBy = $args['orderBy'] ?? 'created_at';
        $sort = $args['sort'] ?? 'desc';
        $query->orderBy($orderBy, $sort);

        // Apply pagination
        $first = $args['first'] ?? 10;
        $page = $args['page'] ?? 1;

        return $query->paginate($first, ['*'], 'page', $page)->items();
    }

    /**
     * Get single delivery agent order
     */
    public function order($rootValue, array $args, $context)
    {
        $deliveryAgent = delivery_graphql()->authorize();


        // Check if agent can access this order
        if (!delivery_graphql()->canAccessOrder($args['id'], $deliveryAgent->id)) {
            return null;
        }

        return DeliveryAgentOrder::where('id', $args['id'])
            ->where('delivery_agent_id', $deliveryAgent->id)
            ->with(['order', 'deliveryAgent'])
            ->first();
    }
}
