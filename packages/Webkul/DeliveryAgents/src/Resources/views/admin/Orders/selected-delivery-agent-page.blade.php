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
            <x-admin::form
            >
                <x-admin::drawer
                    ref="drawerRef"
                    position="right"
                    width="50%"
                >
                        <x-slot:header>
                            <div class="grid h-8 gap-3">
                                <div class="flex items-center justify-between">
                                    <p class="text-xl font-medium dark:text-white">
                                        @lang('deliveryagent::app.select-order.index.select-delivery-agent'){{ $order->id }}
                                    </p>
                                </div>
                            </div>
                            </x-slot>

                            <x-slot:content> <!-- Pass your custom css to customize header -->
                                <x-admin::datagrid
                                    src="{{ route('admin.deliveryagents.index') }}"
                                    :isMultiRow="true"
                                >
                                    <template #header="{
                                     isLoading,
                                     available,
                                     applied,
                                     sort,
                                     performAction
                                     }">
                                        <template v-if="isLoading">
                                            <x-admin::shimmer.datagrid.table.head :isMultiRow="true"/>
                                        </template>
                                        <template v-else>
                                            <div class="grid grid-cols-4 items-center border-b px-4 py-2.5 dark:border-gray-800">
                                                <template v-for="column in ['full_name', 'email', 'phone']" :key="column">
                                                    <div>
                    <span>@{{ {full_name: 'الاسم الكامل',email: 'البريد الإلكتروني',phone: 'رقم الهاتف'}[column] }}</span>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </template>

                                    <template #body="{
                                    isLoading,
                                    available,
                                    applied,
                                    sort,
                                    performAction
                                    }">
                                        <template v-if="isLoading">
                                            <x-admin::shimmer.datagrid.table.body :isMultiRow="true"/>
                                        </template>
                                        <template v-else>
                                            <div
                                                class="grid grid-cols-4 items-center border-b px-4 py-2.5 transition-all hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950"
                                                v-for="record in available.records"
                                            >
                                                <!-- الاسم -->
                                                <p class="text-base font-semibold text-gray-800 dark:text-white">
                                                    @{{ record.full_name }}
                                                </p>

                                                <!-- البريد -->
                                                <p class="text-gray-600 dark:text-gray-300">
                                                    @{{ record.email }}
                                                </p>

                                                <!-- الحالة والهاتف -->
                                                <div class="flex flex-col gap-1.5">
            <span
                :class="{
                    'label-canceled': record.status == '',
                    'label-active': record.status === 1,
                }"
            >
                @{{ record.status
                    ? '@lang('deliveryagent::app.deliveryagents.datagrid.active')'
                    : '@lang('admin::app.customers.customers.index.datagrid.inactive')'
                }}
            </span>

                                                    <p class="text-gray-600 dark:text-gray-300">
                                                        @{{ record.phone ?? 'N/A' }}
                                                    </p>
                                                </div>

                                                <!-- الإجراءات -->
                                                <div class="flex gap-2">
                                                    <form
                                                        method="post"
                                                        :action="`{{ route('admin.orders.assignDeliveryAgent', [':order', ':agent']) }}`
                                                        .replace(':order', orderId)
                                                        .replace(':agent', record.delivery_agents_id)"
                                                    >
                                                        @csrf
                                                        <input type="hidden" name="delivery_agent_id" :value="record.delivery_agents_id" />
                                                        <input type="hidden" name="order_id" :value="orderId" />
                                                        <button
                                                            type="submit"
                                                            class="w-full text-center px-4 py-2 text-sm font-medium bg-blue-500 text-white rounded-md hover:bg-blue-600"
                                                        >
                                                            تعيين
                                                        </button>
                                                    </form>
                                                    <a
                                                        :href="`{{ route('admin.deliveryagents.view', '') }}/${record.delivery_agents_id}`"
                                                        class="w-full text-center px-4 py-2 text-sm font-medium bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700"
                                                    >
                                                        عرض
                                                    </a>
                                                </div>
                                            </div>
                                        </template>
                                    </template>

                                </x-admin::datagrid>
                                </x-slot>


                </x-admin::drawer>
            </x-admin::form>
        </div>

    </script>


    <script type="module">
        app.component('v-selected-delivery-form', {
            template: '#v-selected-delivery-form-template',
            data() {
                return {
                    orderId: {{ $order->id ?? 'null' }}
                };
            },


        });

    </script>
@endPushOnce
