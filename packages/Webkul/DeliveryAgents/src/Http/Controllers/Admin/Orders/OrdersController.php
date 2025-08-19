<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin\Orders;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\DeliveryAgents\Datagrids\Orders\Admin\AdminOrderDataGrid;
use Webkul\DeliveryAgents\Models\DeliveryAgentOrder;
use Webkul\DeliveryAgents\Models\Order;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentRepository;
use Webkul\Sales\Repositories\OrderRepository;

class OrdersController extends Controller
{
    use ValidatesRequests;

    public function __construct(
        protected DeliveryAgentRepository $deliveryAgentRepository,
        protected OrderRepository $orderRepository

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
        $order = $this->orderRepository->findOrFail($request->order_id);
        if ($order->delivery_agent_id) {
            return response()->json([
                'message' => trans('deliveryagent::app.select-order.create.order-has-delivery'),
                'status'  => 'error',
            ], 400);
        }
        $deliveryAgent = $this->deliveryAgentRepository->find($request->delivery_agent_id);

        if ($deliveryAgent && $deliveryAgent->status == 1 && $deliveryAgent->ranges->count() > 0) {
            $order->delivery_agent_id = $request->delivery_agent_id;
            $order->delivery_status = Order::STATUS_ASSIGNED_TO_AGENT;
            $order->save();
            $order->deliveryAssignments()->create([
                'delivery_agent_id' => $deliveryAgent->id,
                'status'            => DeliveryAgentOrder::STATUS_ASSIGNED_TO_AGENT,
                'assigned_at'       => now(),
            ]);

            return response()->json([
                'message' => trans('deliveryagent::app.select-order.create.create-success'),
                'status'  => 'success',
            ]);
        }

        return response()->json([
            'message' => trans('deliveryagent::app.select-order.create.create-error'),
            'status'  => 'error',
        ], 400);

    }
}
