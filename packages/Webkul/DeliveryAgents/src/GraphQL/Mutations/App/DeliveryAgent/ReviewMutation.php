<?php

namespace Webkul\DeliveryAgents\GraphQL\Mutations\App\DeliveryAgent;

use Illuminate\Support\Facades\DB;
use Webkul\DeliveryAgents\Models\DeliveryAgentReview;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentReviewRepository;

class ReviewMutation
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
     * Create review
     */
    public function create($rootValue, array $args, $context)
    {
        try {
            $deliveryAgent = auth('delivery-agent-api')->user();
            
            if (!$deliveryAgent) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.reviews.errors.unauthorized'),
                    'review' => null
                ];
            }

            $orderId = $args['input']['order_id'];
            $rating = $args['input']['rating'];
            $comment = $args['input']['comment'] ?? null;

            // Validate rating
            if ($rating < 1 || $rating > 5) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.reviews.errors.invalid_rating'),
                    'review' => null
                ];
            }

            // Check if review already exists for this order
            $existingReview = DeliveryAgentReview::where('order_id', $orderId)
                ->where('delivery_agent_id', $deliveryAgent->id)
                ->first();

            if ($existingReview) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.reviews.errors.already_exists'),
                    'review' => null
                ];
            }

            DB::beginTransaction();

            $review = DeliveryAgentReview::create([
                'order_id' => $orderId,
                'delivery_agent_id' => $deliveryAgent->id,
                'rating' => $rating,
                'comment' => $comment,
                'status' => DeliveryAgentReview::STATUS_PENDING,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => __('deliveryAgent::app.reviews.success.created'),
                'review' => $review->load(['order', 'deliveryAgent', 'customer'])
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'review' => null
            ];
        }
    }

    /**
     * Update review
     */
    public function update($rootValue, array $args, $context)
    {
        try {
            $deliveryAgent = auth('delivery-agent-api')->user();
            
            if (!$deliveryAgent) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.reviews.errors.unauthorized'),
                    'review' => null
                ];
            }

            $reviewId = $args['input']['id'];
            $rating = $args['input']['rating'] ?? null;
            $comment = $args['input']['comment'] ?? null;

            $review = DeliveryAgentReview::where('id', $reviewId)
                ->where('delivery_agent_id', $deliveryAgent->id)
                ->first();

            if (!$review) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.reviews.errors.not_found'),
                    'review' => null
                ];
            }

            // Check if review can be updated (only pending reviews)
            if ($review->status !== DeliveryAgentReview::STATUS_PENDING) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.reviews.errors.cannot_update'),
                    'review' => null
                ];
            }

            DB::beginTransaction();

            $updateData = [];
            
            if ($rating !== null) {
                if ($rating < 1 || $rating > 5) {
                    return [
                        'success' => false,
                        'message' => __('deliveryAgent::app.reviews.errors.invalid_rating'),
                        'review' => null
                    ];
                }
                $updateData['rating'] = $rating;
            }

            if ($comment !== null) {
                $updateData['comment'] = $comment;
            }

            if (!empty($updateData)) {
                $review->update($updateData);
            }

            DB::commit();

            return [
                'success' => true,
                'message' => __('deliveryAgent::app.reviews.success.updated'),
                'review' => $review->load(['order', 'deliveryAgent', 'customer'])
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'review' => null
            ];
        }
    }

    /**
     * Delete review
     */
    public function delete($rootValue, array $args, $context)
    {
        try {
            $deliveryAgent = auth('delivery-agent-api')->user();
            
            if (!$deliveryAgent) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.reviews.errors.unauthorized')
                ];
            }

            $reviewId = $args['input']['id'];

            $review = DeliveryAgentReview::where('id', $reviewId)
                ->where('delivery_agent_id', $deliveryAgent->id)
                ->first();

            if (!$review) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.reviews.errors.not_found')
                ];
            }

            // Check if review can be deleted (only pending reviews)
            if ($review->status !== DeliveryAgentReview::STATUS_PENDING) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.reviews.errors.cannot_delete')
                ];
            }

            DB::beginTransaction();

            $review->delete();

            DB::commit();

            return [
                'success' => true,
                'message' => __('deliveryAgent::app.reviews.success.deleted')
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
