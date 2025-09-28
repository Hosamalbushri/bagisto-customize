@if ($order->showDeliveryTab())
    <x-shop::tabs.item
    class="max-md:!px-0 max-md:py-1.5"
    :title="trans('deliveryAgent::app.shop.customer.account.orders.view.delivered.delivery')"
>
        @include('DeliveryAgents::shop.reviews.create')
        <v-create-delivery-agent-review-form
            ref="ReviewCreateModal"
        >
        </v-create-delivery-agent-review-form>
        @php
            $show_agent_data = core()->getConfigData('delivery.settings.orders.show_agent_data_to_customer');
            $delivery_agents = $show_agent_data ? \Illuminate\Support\Arr::wrap($order->deliveryAgent) : [];
        @endphp


        <!-- Mobile Layout -->
    <div class="grid gap-4 md:hidden">
        <!-- بيانات المندوب -->
        <x-shop::accordion :is-active="true" class="overflow-hidden rounded-lg !border-none !bg-gray-100">
            <x-slot:header class="!mb-0 rounded-t-md bg-gray-100 !px-4 py-2.5 text-sm font-medium max-sm:py-2">
                {{ __('deliveryAgent::app.shop.customer.account.orders.view.section_titles.agent_info') }}
            </x-slot>

            <x-slot:content class="!bg-gray-100 !p-0">
                @if($show_agent_data && !empty($delivery_agents))
                <div class="rounded-md rounded-t-none border border-t-0 bg-white px-4 py-3 text-sm">
                    @forelse ($delivery_agents as $delivery_agent)
                        <div class="flex justify-between items-center py-2">
                            <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.name') }}</span>
                            <span class="text-black font-medium">{{ $delivery_agent->name ?? '—' }}</span>
                        </div>

                        <div class="flex justify-between items-center py-2 border-t border-gray-100">
                            <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.phone') }}</span>
                            @if($delivery_agent->phone)
                                <a href="tel:{{ $delivery_agent->phone }}"
                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
{{--                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>--}}
                                    </svg>
                                    {{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.call') }}
                                </a>
                            @else
                                <span class="text-gray-400 text-xs">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.no_phone') }}</span>
                            @endif
                        </div>
                    @empty
                        <div class="flex justify-between items-center py-2">
                            <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.name') }}</span>
                            <span class="text-black font-medium">—</span>
                        </div>

                        <div class="flex justify-between items-center py-2 border-t border-gray-100">
                            <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.phone') }}</span>
                            <span class="text-gray-400 text-xs">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.no_phone') }}</span>
                        </div>
                    @endforelse
                </div>
                @endif
                <!-- زر التقييم للهاتف -->
                @if($order->is_delivered && !$order->hasReview() && !empty($order->delivery_agent_id))
                    <div class="px-4 py-3 border-t border-gray-100 flex justify-center">
                        <button
                            class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg border border-navyBlue bg-white px-4 py-3 text-xs font-medium text-navyBlue transition-colors hover:bg-navyBlue hover:text-white"
                            @click="$refs.ReviewCreateModal.openModal()"
                        >


                            <span class="icon-star text-sm"></span>
                            {{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.rate_agent') }}
                        </button>
                    </div>
                @endif
            </x-slot>
        </x-shop::accordion>

        <!-- تفاصيل التوصيل -->
        <x-shop::accordion :is-active="true" class="overflow-hidden rounded-lg !border-none !bg-gray-100">
            <x-slot:header class="!mb-0 rounded-t-md bg-gray-100 !px-4 py-3 text-sm font-medium max-sm:py-2">
                {{ __('deliveryAgent::app.shop.customer.account.orders.view.section_titles.delivery_details') }}
            </x-slot>

            <x-slot:content class="grid gap-2.5 !bg-gray-100 !p-0">
                <div class="rounded-md rounded-t-none border border-t-0 bg-white px-4 py-3 text-xs font-medium">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.delivery_details.shipping_method') }}</span>
                        <span class="text-black">{{ $order->shipping_method ?? '—' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.delivery_details.tracking_number') }}</span>
                        <span class="text-black">{{ $order->tracking_number ?? '—' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.delivery_details.delivery_status') }}</span>
                        <span class="text-black">{{ $order->status ?? '—' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.delivery_details.estimated_delivery') }}</span>
                        <span class="text-black">{{ $order->estimated_delivery_date ?? '—' }}</span>
                    </div>
                </div>
            </x-slot>
        </x-shop::accordion>
    </div>

    <!-- Desktop Layout -->
    <div class="max-md:hidden">
        <div class="grid grid-cols-2 gap-6 max-lg:grid-cols-1">
            <div class="rounded-xl border">
                <div class="border-b bg-zinc-100 px-5 py-3 text-sm font-semibold text-black">
                    {{ __('deliveryAgent::app.shop.customer.account.orders.view.section_titles.agent_info') }}
                </div>

                @if($show_agent_data && !empty($delivery_agents))
                    @forelse ($delivery_agents as $delivery_agent)
                        <div class="px-5 py-4 text-sm">
                        <div class="flex w-full justify-between items-center py-3">
                            <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.name') }}</span>
                            <span class="text-black font-medium">{{ $delivery_agent->name ?? '—' }}</span>
                        </div>

                        <div class="flex w-full justify-between items-center py-3 border-t border-gray-100">
                            <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.phone') }}</span>
                            @if($delivery_agent->phone)
                                <a href="tel:{{ $delivery_agent->phone }}"
                                   class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
{{--                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>--}}
                                    </svg>
                                    {{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.call') }}
                                </a>
                            @else
                                <span class="text-gray-400 text-sm">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.no_phone') }}</span>
                            @endif
                        </div>
                        </div>
                    @empty
                        <div class="px-5 py-4 text-sm">
                            <div class="flex w-full justify-between items-center py-3">
                                <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.name') }}</span>
                                <span class="text-black font-medium">—</span>
                            </div>

                            <div class="flex w-full justify-between items-center py-3 border-t border-gray-100">
                                <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.phone') }}</span>
                                <span class="text-gray-400 text-sm">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.no_phone') }}</span>
                            </div>
                        </div>
                    @endforelse
                @else
                    <div class="px-5 py-4 text-sm">
                        <div class="flex w-full justify-between items-center py-3">
                            <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.name') }}</span>
                            <span class="text-gray-400 text-sm">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.no_phone') }}</span>
                        </div>

                        <div class="flex w-full justify-between items-center py-3 border-t border-gray-100">
                            <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.phone') }}</span>
                            <span class="text-gray-400 text-sm">{{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.no_phone') }}</span>
                        </div>
                    </div>
                @endif
                @if(core()->getConfigData('delivery.settings.orders.allow_agent_rating'))
                @if($order->is_delivered && !$order->hasReview() && !empty($order->delivery_agent_id))
                    <div class="px-5 py-4 border-t border-gray-100">
                        <button
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-navyBlue bg-white px-6 py-3 text-base font-medium text-navyBlue transition-colors"
                            @click="$refs.ReviewCreateModal.openModal()"
                        >
                            <span class="icon-star text-lg"></span>
                            {{ __('deliveryAgent::app.shop.customer.account.orders.view.agent_info.rate_agent') }}
                        </button>
                    </div>
                @endif
                @endif
            </div>

            <!-- بطاقة تفاصيل التوصيل -->
            <div class="rounded-xl border">
                <div class="border-b bg-zinc-100 px-5 py-3 text-sm font-semibold text-black">
                    {{ __('deliveryAgent::app.shop.customer.account.orders.view.section_titles.delivery_details') }}
                </div>

                <div class="grid gap-2 px-5 py-4 text-sm">
                    <div class="flex w-full justify-between gap-x-5">
                        <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.delivery_details.shipping_method') }}</span>
                        <span class="text-black">{{ $order->shipping_method ?? '—' }}</span>
                    </div>

                    <div class="flex w-full justify-between gap-x-5">
                        <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.delivery_details.tracking_number') }}</span>
                        <span class="text-black">{{ $order->tracking_number ?? '—' }}</span>
                    </div>

                    <div class="flex w-full justify-between gap-x-5">
                        <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.delivery_details.delivery_status') }}</span>
                        <span class="text-black">{{ $order->status ?? '—' }}</span>
                    </div>

                    <div class="flex w-full justify-between gap-x-5">
                        <span class="text-zinc-500">{{ __('deliveryAgent::app.shop.customer.account.orders.view.delivery_details.estimated_delivery') }}</span>
                        <span class="text-black">{{ $order->estimated_delivery_date ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-shop::tabs.item>
@endif

