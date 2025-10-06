<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin\Orders;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\DeliveryAgents\Services\DeliveryAgentService;

class OrdersController extends Controller
{
    use ValidatesRequests;

    public function __construct(
        protected DeliveryAgentService $deliveryAgentService,
    ) {}

    public function assignToAgent(Request $request)
    {
        try {
            $orderId = $request->get('order_id');
            $deliveryAgentId = $request->get('delivery_agent_id');

            $result = $this->deliveryAgentService->assignOrderToAgent($orderId, $deliveryAgentId);

            return $this->successResponse($result['message']);

        } catch (\Throwable $e) {
            $result = $this->deliveryAgentService->handleException(
                $e,
                'Failed to assign order to delivery agent',
                [
                    'order_id' => $request->get('order_id'),
                    'agent_id' => $request->get('delivery_agent_id'),
                ]
            );

            return $this->errorResponse($result['message'], $result['code']);
        }
    }

    public function changeStatus($id, Request $request)
    {
        try {
            $status = $request->get('status');
            $deliveryAgentId = $request->get('delivery_agent_id');

            $result = $this->deliveryAgentService->changeOrderStatus($id, $status, $deliveryAgentId);

            return $this->successResponse($result['message']);

        } catch (\Throwable $e) {
            $result = $this->deliveryAgentService->handleException(
                $e,
                'Failed to change order status',
                [
                    'order_id' => $id,
                    'status'   => $request->get('status'),
                    'agent_id' => $request->get('delivery_agent_id'),
                ]
            );

            return $this->errorResponse($result['message'], $result['code']);
        }
    }


    /**
     * Return success response
     */
    private function successResponse(string $message): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => trans($message),
            'status'  => 'success',
        ]);
    }

    /**
     * Return error response
     */
    private function errorResponse(string $message, int $code = 400): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => trans($message),
            'status'  => 'error',
        ], $code);
    }
}
