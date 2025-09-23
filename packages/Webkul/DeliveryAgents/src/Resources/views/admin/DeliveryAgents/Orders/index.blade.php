@if($deliveryFee = core()->getConfigData('delivery.settings.general.enable_delivery_system'))
    @if ($order->canDelivery() && bouncer()->hasPermission('delivery.deliveryAgent.order'))
        @include('DeliveryAgents::admin.DeliveryAgents.Orders.DeliveryAgents.index',['order' => $order])
    @endif
@endif

