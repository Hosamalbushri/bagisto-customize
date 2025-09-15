<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin\DeliveryAgents;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Webkul\DeliveryAgents\Models\DeliveryAgentReview;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentReviewRepository;
use Webkul\Sales\Models\Order as SalesOrder;

class ReviewsController extends Controller
{
    public function __construct(
        protected DeliveryAgentReviewRepository $reviewRepository
    ) {}

    /**
     * Store a new delivery agent review.
     */
    public function store(Request $request): JsonResponse
    {
        // Validate request and return a unified JSON response on failure
        $validator = Validator::make($request->all(), [
            'delivery_agent_id' => 'required|integer|exists:delivery_agents,id',
            'customer_id'       => 'required|integer|exists:customers,id',
            'order_id'          => 'required|integer|exists:orders,id',
            'rating'            => 'required|integer|min:1|max:5',
            'comment'           => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                message: trans('validation.failed'),
                errors: $validator->errors()->toArray(),
            );
        }

        $data = $validator->validated();
        $order = SalesOrder::find($data['order_id']);

        if (! $order) {
            return $this->errorResponse(
                message: 'الطلب غير موجود',
                errors: ['order_id' => ['الطلب المحدد غير موجود']],
                status: 404
            );
        }

        // Ensure no duplicate review for this order
        $alreadyReviewed = DeliveryAgentReview::where('order_id', $data['order_id'])->exists();

        if ($alreadyReviewed) {
            return $this->errorResponse(
                message: trans('deliveryAgent::app.shop.deliveryAgent.review.validation.already_reviewed'),
                errors: ['order_id' => [trans('deliveryAgent::app.shop.deliveryAgent.review.validation.duplicate_review')]],
            );
        }

        // Ensure order is delivered/completed
        if (strtolower((string) $order->status) !== 'completed') {
            return $this->errorResponse(
                message: trans('deliveryAgent::app.shop.deliveryAgent.review.validation.order_not_delivered'),
                errors: ['order_id' => [trans('deliveryAgent::app.shop.deliveryAgent.review.validation.order_not_delivered')]],
            );
        }

        try {
            $review = $this->reviewRepository->create([
                'order_id'          => $data['order_id'],
                'delivery_agent_id' => $data['delivery_agent_id'],
                'customer_id'       => $data['customer_id'],
                'rating'            => $data['rating'],
                'comment'           => $data['comment'] ?? '',
                'status'            => DeliveryAgentReview::STATUS_PENDING,
            ]);

            return $this->successResponse(
                message: trans('deliveryAgent::app.shop.deliveryAgent.review.validation.review_success'),
                data: $review,
                status: 201
            );
        } catch (\Throwable $e) {
            report($e);

            return $this->errorResponse(
                message: trans('deliveryAgent::app.shop.deliveryAgent.review.validation.review_error'),
                errors: ['general' => ['يرجى المحاولة مرة أخرى']],
                status: 500
            );
        }
    }

    /**
     * Build a success JSON response.
     */
    protected function successResponse(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    /**
     * Build an error JSON response.
     */
    protected function errorResponse(string $message, array $errors = [], int $status = 422): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }
}
