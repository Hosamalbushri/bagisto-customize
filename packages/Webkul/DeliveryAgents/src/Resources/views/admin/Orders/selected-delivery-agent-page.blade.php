<v-selected-delivery-form

>
    <div
        class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
    >
        <span class="acma-icon-how_to_reg text-2xl"></span>

        @lang('deliveryagent::app.select-order.index.select-delivery-agent-btn')
    </div>
</v-selected-delivery-form>



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
            @lang('deliveryagent::app.select-order.index.select-delivery-agent-btn')
        </div>

        <div id="selected-form">

            <x-admin::drawer
                ref="drawerRef"
                position="right"
                width="65%"

            >
                <x-slot:header>
                    <div class="grid h-8 gap-3">
                        <div class="flex items-center justify-between">
                            <p class="text-xl font-medium dark:text-white">
                                @lang('deliveryagent::app.select-order.index.select-delivery-agent'){{ $order->id }}
                            </p>
                        </div>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    <x-admin::tabs position="right">
                        <x-admin::tabs.item
                            title="Tab-1"
                            :is-selected="true"
                            class="text-black-600 dark:text-indigo-400 font-semibold hover:text-indigo-800 dark:hover:text-indigo-300"
                        >
{{--                            <div>--}}
{{--                                @include('deliveryagents::admin.Orders.get-delivery-agents-by-states')--}}
{{--                            </div>--}}
                        </x-admin::tabs.item>

                        <x-admin::tabs.item
                            class="container"
                            title="Tab-2"
                        >
                            <div>
                                <div>
                                    @include('deliveryagents::admin.Orders.get-all-delivery-agents')
                                </div>
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
        });
    </script>
@endPushOnce
