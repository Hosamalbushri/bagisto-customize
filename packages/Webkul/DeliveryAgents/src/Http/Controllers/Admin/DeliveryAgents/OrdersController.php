<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin\DeliveryAgents;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentRepository;
use Webkul\Sales\Repositories\OrderRepository;

class OrdersController extends Controller
{
    use ValidatesRequests;
    public function __construct(
        protected DeliveryAgentRepository $deliveryAgentRepository,
        protected OrderRepository $orderRepository

    ) {}

    public function assignToAgent(Request $request)
    {
        $order = $this->orderRepository->findOrFail($request->order_id);
        if ($order->delivery_agent_id) {
            session()->flash('error', trans('deliveryagent::app.select-order.create.order-has-delivery'));
        } else {
            $deliveryAgent = $this->deliveryAgentRepository->find($request->delivery_agent_id);

            if ($deliveryAgent && $deliveryAgent->status == 1 && $deliveryAgent->ranges->count() > 0) {
                $order->delivery_agent_id = $request->delivery_agent_id;
                $order->save();
                session()->flash('success', trans('deliveryagent::app.select-order.create.create-success'));
            } else {
                session()->flash('error', trans('deliveryagent::app.select-order.create.create-error'));
            }

        }

        return redirect()->route('admin.sales.orders.view', $order->id);
    }

}
