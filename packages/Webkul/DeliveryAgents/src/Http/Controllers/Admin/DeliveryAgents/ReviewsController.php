<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin\DeliveryAgents;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Webkul\DeliveryAgents\Datagrids\DeliveryAgent\ReviewDataGrid;
use Webkul\DeliveryAgents\Models\DeliveryAgentReview;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentReviewRepository;
use Webkul\Sales\Models\Order as SalesOrder;

class ReviewsController extends Controller
{
    public function __construct(
        protected DeliveryAgentReviewRepository $reviewRepository
    ) {}

    public function index()
    {
        if (request()->ajax()) {
            return app(ReviewDataGrid::class)->process();
        }
        abort(404);
    }

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

    public function edit(int $id): JsonResponse
    {
        $review = $this->reviewRepository->findOrFail($id);

        // Build a clean, flattened payload expected by the UI
        $payload = [
            'id'                 => $review->id,
            'rating'             => $review->rating,
            'comment'            => $review->comment,
            'status'             => $review->status,
            'created_at'         => core()->formatDate($review->created_at, 'd-m-Y'),
            'customer_name'      => trim(($review->customer->first_name ?? '').' '.($review->customer->last_name ?? '')) ?: null,
            'agent_name'         => trim(($review->deliveryAgent->first_name ?? '').' '.($review->deliveryAgent->last_name ?? '')) ?: null,
            'order_increment_id' => $review->order->increment_id ?? null,
        ];

        return new JsonResponse([
            'data' => $payload,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,disapproved,pending',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                message: trans('validation.failed'),
                errors: $validator->errors()->toArray(),
            );
        }

        try {
            $review = $this->reviewRepository->findOrFail($id);

            // Update the review status
            $review->update([
                'status' => $request->status,
            ]);

            return $this->successResponse(
                message: trans('deliveryAgent::app.review.index.edit.update_success'),
                data: $review,
            );
        } catch (\Exception $e) {
            report($e);

            return $this->errorResponse(
                message: trans('deliveryAgent::app.review.index.edit.update_error'),
                errors: ['general' => [trans('deliveryAgent::app.review.index.edit.update_error')]],
                status: 500
            );
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $review = $this->reviewRepository->findOrFail($id);

            // Delete the review
            $review->delete();

            return $this->successResponse(
                message: trans('deliveryAgent::app.review.index.delete.delete_success'),
            );
        } catch (\Exception $e) {
            report($e);

            return $this->errorResponse(
                message: trans('deliveryAgent::app.review.index.delete.delete_error'),
                errors: ['general' => [trans('deliveryAgent::app.review.index.delete.delete_error')]],
                status: 500
            );
        }
    }

    /**
     * Mass delete the delivery agent reviews.
     */
    public function massDestroy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'indices'   => 'required|array',
            'indices.*' => 'integer|exists:delivery_agent_reviews,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                message: trans('validation.failed'),
                errors: $validator->errors()->toArray(),
            );
        }

        $indices = $request->input('indices');

        try {
            foreach ($indices as $index) {
                Event::dispatch('delivery_agent.review.delete.before', $index);

                $this->reviewRepository->delete($index);

                Event::dispatch('delivery_agent.review.delete.after', $index);
            }

            return $this->successResponse(
                message: trans('deliveryAgent::app.review.index.datagrid.delete.mass-delete-success'),
            );
        } catch (\Exception $e) {
            report($e);

            return $this->errorResponse(
                message: trans('deliveryAgent::app.review.index.datagrid.delete.mass-delete-error'),
                errors: ['general' => [trans('deliveryAgent::app.review.index.datagrid.delete.mass-delete-error')]],
                status: 500
            );
        }
    }

    /**
     * Mass update the delivery agent reviews status.
     */
    public function massUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'indices'   => 'required|array',
            'indices.*' => 'integer|exists:delivery_agent_reviews,id',
            'value'     => 'required|in:approved,disapproved,pending',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                message: trans('validation.failed'),
                errors: $validator->errors()->toArray(),
            );
        }

        $indices = $request->input('indices');
        $status = $request->input('value');

        try {
            foreach ($indices as $id) {
                Event::dispatch('delivery_agent.review.update.before', $id);

                $review = $this->reviewRepository->update([
                    'status' => $status,
                ], $id);

                Event::dispatch('delivery_agent.review.update.after', $review);
            }

            return $this->successResponse(
                message: trans('deliveryAgent::app.review.index.datagrid.update.mass-update-success'),
            );
        } catch (\Exception $e) {
            report($e);

            return $this->errorResponse(
                message: trans('deliveryAgent::app.review.index.datagrid.update.mass-update-error'),
                errors: ['general' => [trans('deliveryAgent::app.review.index.datagrid.update.mass-update-error')]],
                status: 500
            );
        }
    }
}
