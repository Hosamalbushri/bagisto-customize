@if ($order->canDelivery() && bouncer()->hasPermission('delivery.deliveryAgent.order'))
    @include('deliveryagents::admin.Orders.DeliveryAgents.index',['order' => $order])
@endif
