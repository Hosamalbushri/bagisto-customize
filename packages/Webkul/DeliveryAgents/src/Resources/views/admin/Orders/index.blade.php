@if ($order->canShip())
    @include('deliveryagents::admin.Orders.DeliveryAgents.index',['order' => $order])
@endif
