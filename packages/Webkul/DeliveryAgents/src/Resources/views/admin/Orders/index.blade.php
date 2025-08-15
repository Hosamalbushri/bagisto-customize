@if ($order->canShip() && is_null($order->delivery_agent_id))
    @include('deliveryagents::admin.Orders.DeliveryAgents.index',['order' => $order])
@endif
