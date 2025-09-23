<v-orders-DataGrid>

</v-orders-DataGrid>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-orders-DataGrid-template"
    >
        <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
            <div class="flex justify-between">
                <!-- Total Order Count -->
                 <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
                     @lang('deliveryAgent::app.deliveryAgent.view.dataGrid.orders.count')
                 </p>
            </div>

            <x-admin::datagrid
                src="{{ route('admin.deliveryAgents.view', ['id' => $deliveryAgent->id, 'type' => 'orders']) }}"
                ref="dataGrid"
            >
                <!-- Datagrid Header -->
                <template #header="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <template v-if="isLoading">
                        <x-admin::shimmer.datagrid.table.head :isMultiRow="true" />
                    </template>

                    <template v-else>
                        <div class="row grid grid-cols-[0.5fr_0.5fr_1fr] grid-rows-1 items-center border-b border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                            <div
                                class="flex select-none items-center gap-2.5"
                                v-for="(columnGroup, index) in [['increment_id', 'created_at', 'status'], ['base_grand_total', 'method'], ['full_name', 'customer_email', 'location']]"
                            >
                                <p class="text-gray-600 dark:text-gray-300">
                                    <span class="[&>*]:after:content-['_/_']">
                                        <template v-for="column in columnGroup">
                                            <span
                                                class="after:content-['/'] last:after:content-['']"
                                                :class="{
                                                    'font-medium text-gray-800 dark:text-white': applied.sort.column == column,
                                                    'cursor-pointer hover:text-gray-800 dark:hover:text-white': available.columns.find(columnTemp => columnTemp.index === column)?.sortable,
                                                }"
                                                @click="
                                                    available.columns.find(columnTemp => columnTemp.index === column)?.sortable ? sort(available.columns.find(columnTemp => columnTemp.index === column)): {}
                                                "
                                            >
                                                @{{ available.columns.find(columnTemp => columnTemp.index === column)?.label }}
                                            </span>
                                        </template>
                                    </span>

                                    <i
                                        class="align-text-bottom text-base text-gray-800 dark:text-white ltr:ml-1.5 rtl:mr-1.5"
                                        :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                        v-if="columnGroup.includes(applied.sort.column)"
                                    ></i>
                                </p>
                            </div>
                        </div>
                    </template>
                </template>

                <!-- Datagrid Body -->
                <template #body="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <template v-if="isLoading">
                        <x-admin::shimmer.datagrid.table.body :isMultiRow="true" />
                    </template>

                    <template v-else>
                        <div
                            v-if="available.meta.total"
                            class="row grid grid-cols-4 border-b px-4 py-2.5 transition-all hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950"
                            v-for="record in available.records"
                        >
                            <!-- Order Id, Created, Status Section -->
                            <div class="">
                                <div class="flex gap-2.5">
                                    <div class="flex flex-col gap-1.5">
                                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                                            @{{ "@lang('admin::app.sales.orders.index.datagrid.id')".replace(':id', record.increment_id) }}
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @{{ record.created_at }}
                                        </p>

                                        <p v-html="record.status"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Amount, Pay Via, Channel -->
                            <div class="">
                                <div class="flex flex-col gap-1.5">
                                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                                        @{{ $admin.formatPrice(record.base_grand_total) }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.orders.index.datagrid.pay-by', ['method' => ''])@{{ record.method }}
                                    </p>
                                </div>
                            </div>

                            <!-- Customer, Email, Location Section -->
                            <div class="">
                                <div class="flex flex-col gap-1.5">
                                    <p class="text-base text-gray-800 dark:text-white">
                                        @{{ record.full_name }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ record.customer_email }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ record.country_code }}, @{{ record.state_code }}, @{{ record.location }}
                                    </p>
                                </div>
                            </div>

                            <!-- Actions Section -->
                            <div class="flex items-center justify-end gap-x-2">
                                <template v-if="canShowActions(record)">
                                    <template v-for="(button, index) in getAvailableButtons(record)" :key="index">
                                        <button
                                            type="button"
                                            :class="button.buttonClass"
                                            :title="button.title"
                                            @click="changeOrderStatus(record.id, button.status, button.messageConfirm)"
                                            :disabled="isLoading"
                                        >
                                            <span class="text-sm text-black dark:text-white">
                                                @{{ button.text }}
                                            </span>
                                        </button>
                                    </template>
                                </template>

                                <!-- View Order Link -->
                                <a :href="`{{ route('admin.sales.orders.view', '') }}/${record.id}`">
                                    <span class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"></span>
                                </a>
                            </div>
                        </div>

                        <!-- Empty order -->
                        <div v-else class="table-responsive grid w-full">
                            <div class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10">
                                <!-- Placeholder Image -->
                                <img
                                    src="{{ bagisto_asset('images/empty-placeholders/orders.svg') }}"
                                    class="h-20 w-20 dark:mix-blend-exclusion dark:invert"
                                />

                                <div class="flex flex-col items-center">
                                    <p class="text-base font-semibold text-gray-400">
                                        @lang('deliveryAgent::app.deliveryAgent.view.dataGrid.orders.empty-order')
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>
                </template>
            </x-admin::datagrid>
        </div>
    </script>

    <script type="module">
        app.component('v-orders-DataGrid', {
            template: '#v-orders-DataGrid-template',

            data() {
                return {
                    deliveryAgentId: @json($deliveryAgent->id),
                    isLoading: true,
                    buttonConfigs: {
                        accept: {
                            status: 'accepted_by_agent',
                            buttonClass: 'acma-icon-check_circle flex text-black dark:text-white items-center gap-2 cursor-pointer p-2 hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1',
                            title: @json(__('deliveryAgent::app.deliveryAgent.orders.actions.accept_btn')),
                            text: @json(__('deliveryAgent::app.deliveryAgent.orders.actions.accept_btn')),
                            messageConfirm: @json(__('deliveryAgent::app.deliveryAgent.orders.view.accepted-order-confirmation')),
                            permission: 'delivery.deliveryAgent.order.accept',
                            allowedStatuses: ['assigned_to_agent']
                        },
                        reject: {
                            status: 'rejected_by_agent',
                            buttonClass: 'acma-icon-cancel flex text-black dark:text-white items-center gap-2 cursor-pointer p-2 hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1',
                            title: @json(__('deliveryAgent::app.deliveryAgent.orders.actions.reject_btn')),
                            text: @json(__('deliveryAgent::app.deliveryAgent.orders.actions.reject_btn')),
                            messageConfirm: @json(__('deliveryAgent::app.deliveryAgent.orders.view.rejected-order-confirmation')),
                            permission: 'delivery.deliveryAgent.order.reject',
                            allowedStatuses: ['assigned_to_agent', 'accepted_by_agent']
                        },
                        outForDelivery: {
                            status: 'out_for_delivery',
                            buttonClass: 'acma-icon-truck flex text-black dark:text-white items-center gap-2 cursor-pointer p-2 hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1',
                            title: @json(__('deliveryAgent::app.deliveryAgent.orders.actions.out_for_delivery_btn')),
                            text: @json(__('deliveryAgent::app.deliveryAgent.orders.actions.out_for_delivery_btn')),
                            messageConfirm: @json(__('deliveryAgent::app.deliveryAgent.orders.view.out-for-delivery-order-confirmation')),
                            permission: 'delivery.deliveryAgent.order.out_for_delivery',
                            allowedStatuses: ['accepted_by_agent']
                        },
                        delivered: {
                            status: 'delivered',
                            buttonClass: 'acma-icon-inbox-check flex text-black dark:text-white items-center gap-2 cursor-pointer p-2 hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1',
                            title: @json(__('deliveryAgent::app.deliveryAgent.orders.actions.delivered_btn')),
                            text: @json(__('deliveryAgent::app.deliveryAgent.orders.actions.delivered_btn')),
                            messageConfirm: @json(__('deliveryAgent::app.deliveryAgent.orders.view.delivered-order-confirmation')),
                            permission: 'delivery.deliveryAgent.order.delivered',
                            allowedStatuses: ['out_for_delivery']
                        }
                    }
                };
            },

            methods: {
                /**
                 * Change order status
                 */
                changeOrderStatus(orderId, orderStatus, messageConfirm) {
                    this.$emitter.emit('open-confirm-modal', {
                        message: messageConfirm,
                        agree: () => {
                            this.isLoading = true;
                                this.$axios.post(
                                `{{ route('admin.orders.changeStatus', [':order']) }}`
                                    .replace(':order', orderId),
                                {
                                    status: orderStatus,
                                    order_id: orderId,
                                    delivery_agent_id: this.deliveryAgentId
                                }
                            )
                            .then((response) => {
                                this.$emitter.emit('add-flash', {
                                    type: 'success',
                                    message: response.data.message
                                });
                                this.isLoading = false;
                                this.$refs.dataGrid.get();
                            })
                            .catch((error) => {
                                this.isLoading = false;
                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message: error?.response?.data?.message
                                });
                            });
                        },
                    });
                },

                /**
                 * Check if actions can be shown for the record
                 */
                canShowActions(record) {
                    const hideStatuses = ['rejected_by_agent', 'delivered'];
                    return !hideStatuses.includes(record.deliveryStatus);
                },

                /**
                 * Get available buttons for the record based on status and permissions
                 */
                getAvailableButtons(record) {
                    const availableButtons = [];
                    const permissions = {
                        'delivery.deliveryAgent.order.accept': @json(bouncer()->hasPermission('delivery.deliveryAgent.order.accept')),
                        'delivery.deliveryAgent.order.reject': @json(bouncer()->hasPermission('delivery.deliveryAgent.order.reject')),
                        'delivery.deliveryAgent.order.out_for_delivery': @json(bouncer()->hasPermission('delivery.deliveryAgent.order.out_for_delivery')),
                        'delivery.deliveryAgent.order.delivered': @json(bouncer()->hasPermission('delivery.deliveryAgent.order.delivered'))
                    };

                    Object.keys(this.buttonConfigs).forEach(key => {
                        const config = this.buttonConfigs[key];

                        // Check permission
                        if (!permissions[config.permission]) {
                            return;
                        }

                        // Check if current status allows this button
                        if (config.allowedStatuses.includes(record.deliveryStatus)) {
                            availableButtons.push(config);
                        }
                    });

                    return availableButtons;
                },
            },
        })
    </script>
@endpushOnce
