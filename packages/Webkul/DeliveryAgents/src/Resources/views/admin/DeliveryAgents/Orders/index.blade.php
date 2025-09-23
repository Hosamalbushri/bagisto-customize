@if($deliveryFee = core()->getConfigData('delivery.settings.general.enable_delivery_system'))
    @if ($order->canDelivery() && bouncer()->hasPermission('delivery.deliveryAgent.order'))
        @include('DeliveryAgents::admin.Orders.DeliveryAgents.index',['order' => $order])
    @endif
@endif

