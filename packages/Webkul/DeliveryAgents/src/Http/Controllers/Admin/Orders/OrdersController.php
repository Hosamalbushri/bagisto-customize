<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin\Orders;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
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

        $order = $this->orderRepository->findOrFail($orderId);
        $deliveryAgent = $this->deliveryAgentRepository->find($deliveryAgentId);

        if (! $deliveryAgent || $deliveryAgent->status !== 1) {
            return $this->errorResponse('deliveryagent::app.select-order.create.create-error');
        }

        $order->update([
            'delivery_agent_id' => $deliveryAgentId,
            'delivery_status'   => Order::STATUS_ASSIGNED_TO_AGENT,
        ]);

        $order->deliveryAssignments()->updateOrCreate(
            ['delivery_agent_id' => $deliveryAgentId],
            [
                'status'      => Order::STATUS_ASSIGNED_TO_AGENT,
                'assigned_at' => now(),
            ]
        );

        return $this->successResponse('deliveryagent::app.select-order.create.create-success');
    }

    public function changeStatus($id, Request $request)
    {
        try {
            return DB::transaction(function () use ($id, $request) {
                $order = $this->orderRepository
                    ->with(['deliveryAssignments', 'invoices', 'items'])
                    ->findOrFail($id);

                $status = $request->get('status');

                // تحقق من المندوب والحالة
                if (
                    empty($order->delivery_agent_id) ||
                    $order->delivery_agent_id !== $request->get('delivery_agent_id') ||
                    ! in_array($status, [
                        Order::STATUS_ACCEPTED_BY_AGENT,
                        Order::STATUS_REJECTED_BY_AGENT,
                        Order::STATUS_OUT_FOR_DELIVERY,
                        Order::STATUS_DELIVERED,
                    ])
                ) {
                    throw new \Exception('deliveryagent::app.select-order.update.updated-error');
                }

                // تحديث حالة الطلب
                $order->update(['delivery_status' => $status]);

                // تحديث التعيين حسب الحالة
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

                        if (! $order->invoices->count() && $order->canInvoice()) {
                            $this->invoiceRepository->create([
                                'order_id' => $order->id,
                                'invoice'  => [
                                    'items' => $order->items->mapWithKeys(fn ($item) => [
                                        $item->id => $item->qty_to_invoice,
                                    ])->toArray(),
                                ],
                            ]);
                        }
                        if (isset($orderState)) {
                            $this->orderRepository->updateOrderStatus($order, $orderState);
                        } elseif ($order->hasOpenInvoice()) {
                            $this->orderRepository->updateOrderStatus($order, Order::STATUS_PENDING_PAYMENT);
                        } else {
                            $order->update(['status' => Order::STATUS_PROCESSING]);
                        }
                        break;

                    case Order::STATUS_DELIVERED:
                        $this->updateAssignmentStatus($order, Order::STATUS_DELIVERED, [
                            'completed_at' => now(),
                        ]);
                        $this->orderRepository->updateOrderStatus($order, Order::STATUS_COMPLETED);
                        break;
                }

                return $this->successResponse('deliveryagent::app.select-order.update.update-success');
            });
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->errorResponse(
                $e->getMessage() ?: 'deliveryagent::app.select-order.update.update-failed',
                500
            );
        }
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
