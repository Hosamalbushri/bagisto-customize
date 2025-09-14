<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin\Orders;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Webkul\DeliveryAgents\Datagrids\Orders\Admin\AdminOrderDataGrid;
use Webkul\DeliveryAgents\Models\Order;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;

class OrdersController extends Controller
{
    use ValidatesRequests;

    public function __construct(
        protected DeliveryAgentRepository $deliveryAgentRepository,
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository,

    ) {}

    public function index()
    {
        if (request()->ajax()) {
            return datagrid(AdminOrderDataGrid::class)->process();
        }
        abort(422);
    }

    public function assignToAgent(Request $request)
    {
        $orderId = $request->get('order_id');
        $deliveryAgentId = $request->get('delivery_agent_id');

        DB::beginTransaction();

        try {
            $order = $this->orderRepository->findOrFail($orderId);
            $deliveryAgent = $this->deliveryAgentRepository->find($deliveryAgentId);

            if (! $deliveryAgent || (int) $deliveryAgent->status !== 1) {
                DB::rollBack();

                return $this->errorResponse('deliveryAgent::app.select-order.create.create-error');
            }

            $order->update([
                'delivery_agent_id' => $deliveryAgentId,
            ]);

            $order->deliveryAssignments()->updateOrCreate(
                ['delivery_agent_id' => $deliveryAgentId],
                [
                    'status'      => Order::STATUS_ASSIGNED_TO_AGENT,
                    'assigned_at' => now(),
                ]
            );

            if ($order->invoices->isEmpty() && $order->canInvoice()) {
                $this->invoiceRepository->create(
                    [
                        'order_id' => $order->id,
                        'invoice'  => [
                            'items' => $order->items->mapWithKeys(fn ($item) => [
                                $item->id => $item->qty_to_invoice,
                            ])->toArray(),
                        ],
                    ],
                );
            }
            $newStatus = $this->determineOrderStatus($order);
            $this->orderRepository->updateOrderStatus($order, $newStatus);

            DB::commit();

            return $this->successResponse('deliveryAgent::app.select-order.create.create-success');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to assign order to delivery agent', [
                'order_id' => $orderId,
                'agent_id' => $deliveryAgentId,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            return $this->errorResponse('deliveryAgent::app.select-order.create.transaction-failed', 500);
        }
    }

    public function changeStatus($id, Request $request)
    {
        try {
            return DB::transaction(function () use ($id, $request) {
                $order = $this->orderRepository
                    ->with(['deliveryAssignments', 'invoices', 'items'])
                    ->findOrFail($id);

                $status = $request->get('status');
                $deliveryAgentId = $request->get('delivery_agent_id');
                $deliveryAgent = $this->deliveryAgentRepository->find($deliveryAgentId);
                if (! $deliveryAgent || (int) $deliveryAgent->status !== 1) {
                    throw new \Exception('deliveryAgent::app.select-order.create.create-error');
                }

                if (
                    empty($order->delivery_agent_id) ||
                    (int) $order->delivery_agent_id !== $deliveryAgentId ||
                    ! in_array($status, [
                        Order::STATUS_ACCEPTED_BY_AGENT,
                        Order::STATUS_REJECTED_BY_AGENT,
                        Order::STATUS_OUT_FOR_DELIVERY,
                        Order::STATUS_DELIVERED,
                    ])
                ) {
                    throw new \Exception('deliveryAgent::app.select-order.update.updated-error');
                }

                $this->orderRepository->updateOrderStatus($order, $status);

                switch ($status) {
                    case Order::STATUS_ACCEPTED_BY_AGENT:
                        $this->updateAssignmentStatus($order, Order::STATUS_ACCEPTED_BY_AGENT, [
                            'accepted_at' => now(),
                        ]);
                        break;

                    case Order::STATUS_REJECTED_BY_AGENT:
                        $this->updateAssignmentStatus($order, Order::STATUS_REJECTED_BY_AGENT, [
                            'rejected_at' => now(),
                        ]);
                        $order->update(['delivery_agent_id' => null]);
                        break;

                    case Order::STATUS_OUT_FOR_DELIVERY:
                        $this->updateAssignmentStatus($order, Order::STATUS_OUT_FOR_DELIVERY);
                        break;

                    case Order::STATUS_DELIVERED:
                        $this->updateAssignmentStatus($order, Order::STATUS_DELIVERED, [
                            'completed_at'        => now(),
                            'delivery_agent_info' => json_encode([
                                'id'    => $deliveryAgent->id,
                                'name'  => $deliveryAgent->name,   // full name (first + last)
                                'phone' => $deliveryAgent->phone,
                            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE),
                        ]);
                        $order->update(['is_delivered' => true]);
                        $this->orderRepository->updateOrderStatus($order, Order::STATUS_COMPLETED);
                        break;
                }

                return $this->successResponse('deliveryAgent::app.select-order.update.update-success');
            });
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Failed to change order status', [
                'order_id' => $id,
                'status' => $request->get('status'),
                'agent_id' => $request->get('delivery_agent_id'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->errorResponse(
                $e->getMessage() ?: 'deliveryAgent::app.select-order.update.update-failed',
                500
            );
        }
    }

    protected function determineOrderStatus(Order $order): string
    {
        if (isset($order->state)) {
            return $order->state;
        }

        if ($order->hasOpenInvoice()) {
            return Order::STATUS_PENDING_PAYMENT;
        }

        return Order::STATUS_ASSIGNED_TO_AGENT;
    }

    private function updateAssignmentStatus(Order $order, string $status, array $extra = []): void
    {
        $order->deliveryAssignments()
            ->where('delivery_agent_id', $order->delivery_agent_id)
            ->update(array_merge(['status' => $status], $extra));
    }

    private function successResponse(string $message)
    {
        return response()->json([
            'message' => trans($message),
            'status'  => 'success',
        ]);
    }

    private function errorResponse(string $message, int $code = 400)
    {
        return response()->json([
            'message' => trans($message),
            'status'  => 'error',
        ], $code);
    }
}
