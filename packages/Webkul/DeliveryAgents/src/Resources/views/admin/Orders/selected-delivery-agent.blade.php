@if ($order->canShip() && is_null($order->delivery_agent_id))
        @include('deliveryagents::admin.Orders.selected-delivery-agent-page',['order' => $order])
@endif
