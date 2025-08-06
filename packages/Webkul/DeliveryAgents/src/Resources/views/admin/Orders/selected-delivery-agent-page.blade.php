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
                                src="{{ route('admin.deliveryagents.order.select-delivery-agent') }}"
                            >
                                <template #header="{
                                     isLoading,
                                     available,
                                     applied,
                                     sort,
                                     performAction
                                     }">
                                    <template v-if="isLoading">
                                        <x-admin::shimmer.datagrid.table.head/>
                                    </template>
                                    <template v-else>
                                        <div
                                            class="row grid grid-cols-[2fr_1fr_1fr] grid-rows-1 items-center border-b px-4 py-2.5 dark:border-gray-800">
                                            <div
                                                class="flex select-none items-center gap-2.5"
                                                v-for="(columnGroup, index) in [['full_name', 'email'], ['status', 'phone']]"
                                            >
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
                                                <i class="align-text-bottom text-base text-gray-800 dark:text-white ltr:ml-1.5 rtl:mr-1.5"
                                                   :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                                   v-if="columnGroup.includes(applied.sort.column)"></i></div>
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
                                            class="row grid grid-cols-[minmax(150px,_2fr)_1fr_1fr] border-b px-4 py-2.5 transition-all hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950"
                                            v-for="record in available.records"
                                        >
                                            <div class="flex gap-2.5">

                                                <div class="flex flex-col gap-1.5">
                                                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                                                        @{{ record.full_name }}
                                                    </p>

                                                    <p class="text-gray-600 dark:text-gray-300">
                                                        @{{ record.email }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex flex-col gap-1.5">
                                                <div class="flex gap-1.5">
                                                        <span
                                                            :class="{
                                                            'label-canceled': record.status == '',
                                                            'label-active': record.status === 1,
                                                            }"
                                                        >
                                                            @{{ record.status ? '@lang('deliveryagent::app.deliveryagents.datagrid.active')' : '@lang('admin::app.customers.customers.index.datagrid.inactive')' }}
                                                        </span>
                                                </div>
                                                <p class="text-gray-600 dark:text-gray-300">
                                                    @{{ record.phone ?? 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="flex w-full flex-col gap-1.5">
                                                <div class="flex w-full justify-end gap-2">

                                                    <form
                                                        method="post"
                                                        :action="`{{ route('admin.orders.assignDeliveryAgent', [':order', ':agent']) }}`
                                                        .replace(':order', orderId)
                                                        .replace(':agent', record.delivery_agents_id)"
                                                    >
                                                        @csrf
                                                        <input type="hidden" name="delivery_agent_id"
                                                               :value="record.delivery_agents_id"/>
                                                        <input type="hidden" name="order_id" :value="orderId"/>
                                                        <button
                                                            type="submit"
                                                            class="acma-icon-plus1 rtl:acma-icon-checkmark cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                                                        >

                                                        </button>
                                                    </form>
                                                    <a
                                                        :href="`{{ route('admin.deliveryagents.view', '') }}/${record.delivery_agents_id}`"
                                                        class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                                                    >

                                                    </a>
                                                </div>
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
