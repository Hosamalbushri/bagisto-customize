<x-admin::datagrid
    src="{{ route('admin.deliveryAgents.order.select-delivery-agent') }}?area_id={{ $order->shipping_address->state_area_id }}"
    ref="deliveryAgentDatagrid"
    :isMultiRow="true"
>
    @include('DeliveryAgents::admin.DeliveryAgents.Orders.DeliveryAgents.components.delivery-agents-datagrid', [
        'orderId' => $order->id
    ])
</x-admin::datagrid>


