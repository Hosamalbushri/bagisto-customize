@php
    $hasPermission = bouncer()->hasPermission('delivery.deliveryAgent.edit') ||
                    bouncer()->hasPermission('delivery.deliveryAgent.delete') ||
                    bouncer()->hasPermission('delivery.deliveryAgent.order.assign-delivery-agent');
@endphp
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
        <x-admin::shimmer.datagrid.table.head />
    </template>
    <template v-else>
        <div class="row grid grid-cols-[2fr_1fr_1fr] grid-rows-1 items-center border-b border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div
                class="flex select-none items-center gap-2.5"
                v-for="(columnGroup, index) in [['full_name', 'email','phone'], ['status','current_orders_count','average_rating']]"
            >
                @if ($hasPermission)
                    <label
                        class="flex w-max cursor-pointer select-none items-center gap-1"
                        for="mass_action_select_all_records"
                        v-if="! index"
                    >
                        <input
                            type="checkbox"
                            name="mass_action_select_all_records"
                            id="mass_action_select_all_records"
                            class="peer hidden"
                            :checked="['all', 'partial'].includes(applied.massActions.meta.primary_column)"
                            @change="selectAll"
                        >
                        <span
                            class="icon-uncheckbox cursor-pointer rounded-md text-2xl"
                            :class="[
                                applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-checked peer-checked:active-checkbox' :
                                (applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-checkbox-partial peer-checked:active-checkbox' : '')
                            ]"
                        ></span>
                    </label>
                @endif

                <span class="[&>*]:after:content-['_/_']">
                    <template v-for="column in columnGroup">
                        <span
                            class="after:content-['/'] last:after:content-['']"
                            :class="{
                                'font-medium text-gray-800 dark:text-white': applied.sort.column == column,
                                'cursor-pointer hover:text-gray-800 dark:hover:text-white': available.columns.find(columnTemp => columnTemp.index === column)?.sortable,
                            }"
                            @click="
                                available.columns.find(columnTemp => columnTemp.index === column)?.sortable ?
                                sort(available.columns.find(columnTemp => columnTemp.index === column)): {}
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
            </div>
        </div>
    </template>
</template>

<!-- Datagrid Body -->
<template #body="{
    isLoading,
    available,
    applied,
    sort,
    performAction
}">
    <template v-if="isLoading">
        <x-admin::shimmer.datagrid.table.body :isMultiRow="true" />
    </template>

    <template v-else>
        <div
            class="row grid grid-cols-[minmax(150px,_2fr)_1fr_1fr] border-b px-4 py-2.5 transition-all hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950"
            v-for="record in available.records"
        >
            <!-- Agent Information Section -->
            <div class="flex gap-2.5">
                @if ($hasPermission)
                    <input
                        type="checkbox"
                        :name="`mass_action_select_record_${record.delivery_agents_id}`"
                        :id="`mass_action_select_record_${record.delivery_agents_id}`"
                        :value="record.delivery_agents_id"
                        class="peer hidden"
                        v-model="applied.massActions.indices"
                        @change="setCurrentSelectionMode"
                    >

                    <label
                        class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:active-checkbox"
                        :for="`mass_action_select_record_${record.delivery_agents_id}`"
                    ></label>
                @endif

                <div class="flex flex-col gap-1.5">
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        @{{ record.full_name }}
                    </p>

                    <p class="text-gray-600 dark:text-gray-300">
                        @{{ record.email }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-300">
                        @{{ record.phone ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <!-- Status and Contact Section -->
            <div class="flex flex-col gap-1.5">
                <div class="flex gap-1.5">
                    <p v-html="record.status"></p>
                </div>
                <p v-html="record.current_orders_count"></p>

                <p class="flex">
                    <x-admin::star-rating
                        :is-editable="false"
                        ::value="record.average_rating"
                    />
                </p>
            </div>

            <!-- Actions Section -->
            <div class="flex w-full flex-col gap-1.5">
                <div class="flex w-full justify-end gap-1">
                    @if (bouncer()->hasPermission('delivery.deliveryAgent.order.assign-delivery-agent'))
                        <v-delivery-assignment
                            :orderId="{{ $orderId ?? 'null' }}"
                            :agentId="record.delivery_agents_id"
                        ></v-delivery-assignment>
                    @endif
                    <a
                        :href="`{{ route('admin.deliveryAgents.view', '') }}/${record.delivery_agents_id}`"
                        class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-3xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                        title="@lang('admin::app.datagrid.view')"
                    ></a>
                </div>
            </div>
        </div>
    </template>
</template>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-delivery-assignment-template"
    >
        <x-admin::button
            button-type="submit"
            class="acma-icon-fact_check flex text-black dark:text-white  items-center gap-2 cursor-pointer p-2 hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
            :title="trans('deliveryAgent::app.select-order.index.assign-btn')"
            @click="assignDelivery(orderId, agentId)"
            ::loading="isLoading"
            ::disabled="isLoading"
        >
            <span class="text-sm text-black dark:text-white">
                @lang('deliveryAgent::app.select-order.index.assign-btn')
            </span>
        </x-admin::button>
    </script>

    <script type="module">
        app.component('v-delivery-assignment', {
            template: '#v-delivery-assignment-template',

            props: {
                orderId: {
                    type: [Number, String],
                    required: true
                },
                agentId: {
                    type: [Number, String],
                    required: true
                }
            },

            data() {
                return {
                    globalLoading: false,
                };
            },

            computed: {
                isLoading() {
                    return this.globalLoading;
                }
            },

            mounted() {
                // Listen for global loading state changes
                this.$emitter.on('set-delivery-assignment-loading', (loading) => {
                    this.globalLoading = loading;
                });
            },

            beforeUnmount() {
                // Clean up event listener
                this.$emitter.off('set-delivery-assignment-loading');
            },

            methods: {
                /**
                 * Assign delivery agent to order
                 * @param {number} orderId - Order ID
                 * @param {number} agentId - Delivery Agent ID
                 */
                assignDelivery(orderId, agentId) {
                    this.$emitter.emit('open-confirm-modal', {
                        message: "@lang('deliveryAgent::app.select-order.index.assign-delivery-agent-confirmation')",
                        agree: () => {
                            // Set loading state for all buttons
                            this.$emitter.emit('set-delivery-assignment-loading', true);
                            
                            this.$axios.post(
                                `{{ route('admin.orders.assignDeliveryAgent', [':order', ':agent']) }}`
                                    .replace(':order', orderId)
                                    .replace(':agent', agentId),
                                {
                                    delivery_agent_id: agentId,
                                    order_id: orderId
                                }
                            )
                            .then((response) => {
                                this.$emitter.emit('add-flash', {
                                    type: 'success',
                                    message: response.data.message
                                });
                                window.location.reload();
                            })
                            .catch((error) => {
                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message: error?.response?.data?.message || 'حدث خطأ أثناء تعيين وكيل التوصيل'
                                });
                                // Reset loading state on error
                                this.$emitter.emit('set-delivery-assignment-loading', false);
                            });
                        },
                    });
                },
            },
        });
    </script>
@endpushOnce
