{{--{{$order->getStateIdFromCode($order->shipping_address->state)}}--}}
{{--{{$order->shipping_address->state_area_id}}--}}
{{--<!-- Shipment Information-->--}}
{{--<x-admin::accordion>--}}
{{--    <x-slot:header>--}}
{{--        <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">--}}
{{--            @lang('deliveryagent::app.orders.view.delivery-agent')--}}
{{--        </p>--}}
{{--    </x-slot>--}}

{{--    <x-slot:content>--}}
{{--        @forelse (\Illuminate\Support\Arr::wrap($order->deliveryAgent) as $delivery_agent)--}}
{{--            <div class="grid gap-y-2.5">--}}
{{--                <div>--}}
{{--                    <!-- Shipment Id -->--}}
{{--                    <p class="font-semibold text-gray-800 dark:text-white">--}}
{{--                        @lang('admin::app.sales.orders.view.shipment', ['shipment' => $shipment->id])--}}
{{--                        {{$delivery_agent->id}}--}}
{{--                    </p>--}}

{{--                    <!-- Shipment Created -->--}}
{{--                    <p class="text-gray-600 dark:text-gray-300">--}}
{{--                        {{ core()->formatDate($shipment->created_at, 'd M, Y H:i:s a') }}--}}
{{--                    </p>--}}
{{--                </div>--}}

{{--                <div class="flex gap-2.5">--}}
{{--                    <a--}}
{{--                        href="{{ route('admin.sales.shipments.view', $shipment->id) }}"--}}
{{--                        class="text-sm text-blue-600 transition-all hover:underline"--}}
{{--                    >--}}
{{--                        @lang('admin::app.sales.orders.view.view')--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @empty--}}
{{--            <p class="text-gray-600 dark:text-gray-300">--}}
{{--                @lang('admin::app.sales.orders.view.no-shipment-found')--}}
{{--            </p>--}}
{{--        @endforelse--}}
{{--    </x-slot>--}}
{{--</x-admin::accordion>--}}
