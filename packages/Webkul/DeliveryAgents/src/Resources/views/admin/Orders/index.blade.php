@if ($order->canDelivery() && bouncer()->hasPermission('delivery.deliveryAgent.order'))
    @include('DeliveryAgents::admin.Orders.DeliveryAgents.index',['order' => $order])
@endif
