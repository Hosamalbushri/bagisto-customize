{{--{{$order->getStateIdFromCode($order->shipping_address->state)}}--}}
{{--{{$order->shipping_address->state_area_id}}--}}
<!-- Shipment Information-->

<x-admin::accordion>
    <x-slot:header>
        <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
            @lang('deliveryagent::app.orders.view.delivery')
        </p>
    </x-slot>

    <x-slot:content>
        @forelse (\Illuminate\Support\Arr::wrap($order->deliveryAgent) as $delivery_agent)

            <div class="{{ $order->deliveryAgent ? 'pb-4' : '' }}">
                <div class="flex flex-col gap-1.5">
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ $delivery_agent->name }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-300">
                        <span>{{ __('deliveryagent::app.orders.view.contact') }}: {{ $delivery_agent->phone }}</span>
                    </p>
                </div>
            </div>
            <div class="grid gap-y-2.5">
                <div class="flex gap-2.5">
                    <a
                        href="{{ route('admin.deliveryagents.view', $delivery_agent->id) }}"
                        class="text-sm text-blue-600 transition-all hover:underline"
                    >
                        @lang('deliveryagent::app.orders.view.view')
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-600 dark:text-gray-300">
                @lang('deliveryagent::app.orders.view.no-delivery-agent-found')
            </p>
        @endforelse
    </x-slot>
</x-admin::accordion>
