@if ($order->canShip())
        @include('deliveryagents::admin.Orders.selected-delivery-agent-page',['order' => $order])
@endif
