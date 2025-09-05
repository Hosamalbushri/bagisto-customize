@if ($order->canDelivery() && bouncer()->hasPermission('delivery.deliveryAgent.order'))
    <v-selected-delivery-form
>
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

        <div
            class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
            @click="$refs.drawerRef.open()"
        >
      <span class="acma-icon-how_to_reg text-2xl"
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

        <div id="selected-form">

            <x-admin::drawer
                ref="drawerRef"
                position="right"
                width="55%"
                class="dark:bg-gray-900 bg-white"

            >
                <x-slot:header>
                    <div class="grid h-8 gap-3">
                        <div class="flex items-center justify-between">
                            <p class="text-xl font-medium dark:text-white">
                                @lang('deliveryAgent::app.select-order.index.select-delivery-agent'){{ $order->id }}
                            </p>
                        </div>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    <x-admin::tabs position="right" class="mt-4">
                        <x-admin::tabs.item
                            title="{{ __('deliveryAgent::app.select-order.index.tabs.in-the-same-area', ['city' => $order->shipping_address->city]) }}"
                            :is-selected="true"
                            class="p-4 bg-gray-50 dark:bg-gray-900 rounded-md transition-all"
                        >
                            <div class="space-y-3">
                                @include('DeliveryAgents::admin.Orders.DeliveryAgents.get-delivery-agents-by-area')
                            </div>
                        </x-admin::tabs.item>

                        <x-admin::tabs.item
                            title="{{ __('deliveryAgent::app.select-order.index.tabs.all') }}"
                            class="p-4 bg-gray-50 dark:bg-gray-900 rounded-md transition-all"
                        >
                            <div class="space-y-3">
                                @include('DeliveryAgents::admin.Orders.DeliveryAgents.get-all-delivery-agents')
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

            },
            mounted() {
                this.$emitter.on('request-assign-delivery', ({ orderId, agentId }) => {
                    this.assignDelivery(orderId, agentId);
                });
            },

            beforeUnmount() {
                this.$emitter.off('request-assign-delivery');
            },

            methods: {
                assignDelivery(orderId, agentId) {
                    this.$emitter.emit('open-confirm-modal', {
                        message: "@lang('deliveryAgent::app.select-order.index.assign-delivery-agent-confirmation')",
                        agree: () => {
                            this.$axios.post(
                                `{{ route('admin.orders.assignDeliveryAgent', [':order', ':agent']) }}`
                                    .replace(':order', orderId)
                                    .replace(':agent', agentId),
                                { delivery_agent_id: agentId, order_id: orderId }
                            )
                                .then((response) => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    window.location.reload();
                                })
                                .catch((error) => {
                                    this.$emitter.emit('add-flash', {
                                        type: 'error',
                                        message: error?.response?.data?.message
                                    });
                                });
                        },
                    });
                },

            },
            });
    </script>
@endPushOnce
