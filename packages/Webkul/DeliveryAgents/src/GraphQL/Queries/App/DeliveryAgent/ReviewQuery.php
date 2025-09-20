<?php

namespace Webkul\DeliveryAgents\GraphQL\Queries\App\DeliveryAgent;

use Webkul\DeliveryAgents\Models\DeliveryAgentReview;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentReviewRepository;

class ReviewQuery
{
    /**
     * @var DeliveryAgentReviewRepository
     */
    protected $deliveryAgentReviewRepository;

    public function __construct(
        DeliveryAgentReviewRepository $deliveryAgentReviewRepository
    ) {
        $this->deliveryAgentReviewRepository = $deliveryAgentReviewRepository;
    }

    /**
     * Get delivery agent reviews
     */
    public function reviews($rootValue, array $args, $context)
    {
        $deliveryAgent = delivery_graphql()->authorize();



        $query = DeliveryAgentReview::where('delivery_agent_id', $deliveryAgent->id)
            ->with(['order', 'deliveryAgent', 'customer']);

        // Apply filters
        if (isset($args['status']) && $args['status']) {
            $query->where('status', $args['status']);
        }

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
     * Get single delivery agent review
     */
    public function review($rootValue, array $args, $context)
    {
        $deliveryAgent = delivery_graphql()->authorize();


        return DeliveryAgentReview::where('id', $args['id'])
            ->where('delivery_agent_id', $deliveryAgent->id)
            ->with(['order', 'deliveryAgent', 'customer'])
            ->first();
    }
}
