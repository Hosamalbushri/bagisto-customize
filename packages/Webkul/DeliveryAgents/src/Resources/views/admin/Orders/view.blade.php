<x-admin::accordion>
    <x-slot:header>
        <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
            @lang('deliveryAgent::app.orders.view.delivery')
        </p>
    </x-slot>

    <x-slot:content>
        @php
            // Try to fetch the delivered assignment and decode stored JSON info
            $deliveredAssignment = $order->relationLoaded('deliveredDeliveryAssignment')
                ? $order->deliveredDeliveryAssignment
                : $order->deliveredDeliveryAssignment()->first();

            $deliveryInfo = null;
            if ($deliveredAssignment && $deliveredAssignment->delivery_agent_info) {
                $decoded = json_decode($deliveredAssignment->delivery_agent_info, true);
                $deliveryInfo = is_array($decoded) ? $decoded : null;
            }
        @endphp

        @if ($deliveryInfo)
            <div class="pb-4">
                <div class="flex flex-col gap-1.5">
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ $deliveryInfo['name'] ?? '' }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-300">
                        <span>{{ __('deliveryAgent::app.orders.view.contact') }}: {{ $deliveryInfo['phone'] ?? '' }}</span>
                    </p>
                </div>
            </div>

            @if (!empty($deliveredAssignment->delivery_agent_id))
                <div class="grid gap-y-2.5">
                    <div class="flex gap-2.5">
                        <a
                            href="{{ route('admin.deliveryAgents.view', $deliveredAssignment->delivery_agent_id) }}"
                            class="text-sm text-blue-600 transition-all hover:underline"
                        >
                            @lang('deliveryAgent::app.orders.view.view')
                        </a>
                    </div>
                </div>
            @endif
        @else
            @forelse (\Illuminate\Support\Arr::wrap($order->deliveryAgent) as $delivery_agent)

                <div class="{{ $order->deliveryAgent ? 'pb-4' : '' }}">
                    <div class="flex flex-col gap-1.5">
                        <p class="font-semibold text-gray-800 dark:text-white">
                            {{ $delivery_agent->name }}
                        </p>
                        <p class="text-gray-600 dark:text-gray-300">
                            <span>{{ __('deliveryAgent::app.orders.view.contact') }}: {{ $delivery_agent->phone }}</span>
                        </p>
                    </div>
                </div>
                <div class="grid gap-y-2.5">
                    <div class="flex gap-2.5">
                        <a
                            href="{{ route('admin.deliveryAgents.view', $delivery_agent->id) }}"
                            class="text-sm text-blue-600 transition-all hover:underline"
                        >
                            @lang('deliveryAgent::app.orders.view.view')
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 dark:text-gray-300">
                    @lang('deliveryAgent::app.orders.view.no-delivery-agent-found')
                </p>
            @endforelse
        @endif
    </x-slot>
</x-admin::accordion>
