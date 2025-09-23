@if ($order->canDelivery() && bouncer()->hasPermission('delivery.deliveryAgent.order'))
    <v-selected-delivery-form>
        <div
            class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
        >
            <span class="acma-icon-how_to_reg text-2xl"></span>
            @if($order->isRejected())
                @lang('deliveryAgent::app.select-order.index.reselect-delivery-agent-btn')
            @else
                @lang('deliveryAgent::app.select-order.index.select-delivery-agent-btn')
            @endif
        </div>
    </v-selected-delivery-form>
@endif

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-selected-delivery-form-template"
    >
        <!-- Delivery Agent Selection Button -->
        <div
            class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
            :class="{ 'opacity-50 cursor-not-allowed pointer-events-none': isSubmitting }"
            @click="!isSubmitting && $refs.drawerRef.open()"
        >
            <span
                class="acma-icon-how_to_reg text-2xl"
                role="button"
                tabindex="0"
            >
            </span>
            @if($order->isRejected())
                @lang('deliveryAgent::app.select-order.index.reselect-delivery-agent-btn')
            @else
                @lang('deliveryAgent::app.select-order.index.select-delivery-agent-btn')
            @endif
        </div>

        <!-- Delivery Agent Selection Form -->
        <div id="selected-form">
            <x-admin::drawer
                ref="drawerRef"
                position="right"
                width="55%"
                class="dark:bg-gray-900 bg-white"
            >
                <x-slot:header>
                    <div class="relative flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            @lang('deliveryAgent::app.select-order.index.select-delivery-agent'){{ $order->id }}
                        </h2>
                    </div>
                </x-slot:header>
                <x-slot:content>
                    <x-admin::tabs position="right" class="mt-4">
                        <!-- Same Area Tab -->
                        <x-admin::tabs.item
                            title="{{ __('deliveryAgent::app.select-order.index.tabs.in-the-same-area', ['city' => $order->shipping_address->city]) }}"
                            :is-selected="true"
                        >
                            <div class="space-y-3">
                                @include('DeliveryAgents::admin.DeliveryAgents.Orders.DeliveryAgents.get-delivery-agents-by-area')
                            </div>
                        </x-admin::tabs.item>

                        <!-- All Delivery Agents Tab -->
                        <x-admin::tabs.item
                            title="{{ __('deliveryAgent::app.select-order.index.tabs.all') }}"
                        >
                            <div class="space-y-3">
                                @include('DeliveryAgents::admin.DeliveryAgents.Orders.DeliveryAgents.get-all-delivery-agents')
                            </div>
                        </x-admin::tabs.item>
                    </x-admin::tabs>
                </x-slot:content>
            </x-admin::drawer>
        </div>
    </script>

    <script type="module">
        app.component('v-selected-delivery-form', {
            template: '#v-selected-delivery-form-template',
            data() {
                return {
                    isLoading: false,
                };
            },
        });
    </script>
@endPushOnce
