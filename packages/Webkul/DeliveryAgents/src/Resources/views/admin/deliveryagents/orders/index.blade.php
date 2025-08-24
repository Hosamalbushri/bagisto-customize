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
                    @lang('deliveryagent::app.deliveryagents.view.datagrid.orders.count', ['count' => count($deliveryAgent->orders)])
                </p>

            </div>
            <x-admin::datagrid
                :src="route('admin.deliveryagents.view', [
            'id'   => $deliveryAgent->id,
            'type' => 'orders'
        ])"
                ref="Datagrid"
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
                                        <p
                                            class="text-base font-semibold text-gray-800 dark:text-white"
                                        >
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
                                        @{{ record.location }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-x-2">
                                <template v-if="canShowActions(record)">

                                <template v-if="showAcceptButton(record)">

                                    <button
                                    class="acma-icon-check_circle text-xl  cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                                    @click="$emitter.emit('update-order-status', {orderId:record.id,orderStatus:'accepted_by_agent',messageConfirm:acceptMessageConfirm})"
                                >
                                </button>
                                </template>
                                    <template v-if="showRejectButton(record)">
                                        <button
                                            class="acma-icon-cancel text-xl   p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                                            @click="$emitter.emit('update-order-status', {orderId:record.id,orderStatus:'rejected_by_agent',messageConfirm: rejectMessageConfirm
                                    })"
                                        >
                                        </button>
                                    </template>
                                 <template v-if="showOutForDeliveryButton(record)">
                                     <button
                                         class="acma-icon-send1 text-xl   p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                                         @click="$emitter.emit('update-order-status', {orderId:record.id,orderStatus:'out_for_delivery',messageConfirm: rejectMessageConfirm
                                    })"
                                     >
                                     </button>
                                 </template>
                                    <template v-if="showDeliveredButton(record)">
                                        <button
                                            class="icon-repeat text-xl   p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                                            @click="$emitter.emit('update-order-status', {orderId:record.id,orderStatus:'delivered',messageConfirm: rejectMessageConfirm
                                    })"
                                        >
                                        </button>
                                    </template>

                                </template>
                                <a :href="`{{ route('admin.sales.orders.view', '') }}/${record.id}`">
                                    <span class="icon-sort-right text-2xl rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"></span>
                                </a>


                            </div>
                        </div>

                        <div v-else class="table-responsive grid w-full">
                            <div class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10">
                                <!-- Placeholder Image -->
                                <img
                                    src="{{ bagisto_asset('images/empty-placeholders/orders.svg') }}"
                                    class="h-20 w-20 dark:mix-blend-exclusion dark:invert"
                                />

                                <div class="flex flex-col items-center">
                                    <p class="text-base font-semibold text-gray-400">
                                        @lang('deliveryagent::app.deliveryagents.view.datagrid.orders.empty-order')
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
                return{
                    deliveryAgentId :@json($deliveryAgent->id),
                    acceptMessageConfirm:@json(__('deliveryagent::app.deliveryagents.orders.view.accepted-order-confirmation')),
                    rejectMessageConfirm:@json(__('deliveryagent::app.deliveryagents.orders.view.rejected-order-confirmation')),
                };
            },
            mounted() {
                this.$emitter.on('update-order-status', ({orderId,orderStatus,messageConfirm}) => {
                    this.rejectOrder(orderId,orderStatus,messageConfirm);
                });
            },
            beforeUnmount() {
                this.$emitter.off('update-order-status');
            },
            methods: {
                rejectOrder(orderId,orderStatus,messageConfirm) {
                    this.$emitter.emit('open-confirm-modal', {
                        message:messageConfirm ,
                        agree: () => {
                            this.$axios.post(
                                `{{ route('admin.orders.changeStatus', [':order']) }}`
                                    .replace(':order', orderId),
                                { status: orderStatus , order_id: orderId ,delivery_agent_id:this.deliveryAgentId }
                            )
                                .then((response) => {
                                    this.$emitter.emit('add-flash', {
                                        type: 'success',
                                        message: response.data.message
                                    });
                                    this.$refs.Datagrid.get();
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
                canShowActions(record) {
                    // لا تظهر الأزرار لو تم الرفض أو تم التوصيل
                    const hideStatuses = ['rejected_by_agent', 'delivered'];
                    return !hideStatuses.includes(record.deliveryStatus);
                },
                showAcceptButton(record) {
                    // يظهر فقط إذا الطلب جديد أو معين للمندوب
                    const allowedStatuses = ['assigned_to_agent'];
                    return allowedStatuses.includes(record.deliveryStatus);
                },
                showRejectButton(record) {
                    // يظهر فقط إذا الطلب جديد أو معين للمندوب
                    const allowedStatuses = ['assigned_to_agent','accepted_by_agent'];
                    return allowedStatuses.includes(record.deliveryStatus);
                },
                showOutForDeliveryButton(record) {
                    // يظهر بعد قبول الطلب
                    return record.deliveryStatus === 'accepted_by_agent';
                },
                showDeliveredButton(record) {
                    return record.deliveryStatus === 'out_for_delivery';
                },
            },


        })
    </script>
@endpushonce

