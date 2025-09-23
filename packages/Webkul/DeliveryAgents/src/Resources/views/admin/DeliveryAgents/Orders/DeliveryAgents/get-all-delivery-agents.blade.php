<x-admin::datagrid
    src="{{ route('admin.deliveryAgents.index') }}"
    ref="deliveryAgentDatagrid"
    :isMultiRow="true"
>
    @include('DeliveryAgents::admin.Orders.DeliveryAgents.components.delivery-agents-datagrid', [
        'orderId' => $order->id ?? null
    ])
</x-admin::datagrid>

