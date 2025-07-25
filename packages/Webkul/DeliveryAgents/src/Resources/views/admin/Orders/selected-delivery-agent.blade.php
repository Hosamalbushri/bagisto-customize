<x-admin::drawer
    position="right"
    width="50%"

>

    <x-slot:toggle>
        <div
            class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
        >
            <span class="acma-icon-how_to_reg text-2xl"></span>

            @lang('deliveryagent::app.order.index.select-delivery-agent-btn')
        </div>
    </x-slot>


    <x-slot:header>  <!-- Pass your custom css to customize header -->
        Drawer Header
    </x-slot>

    <x-slot:content class="!p-5"> <!-- Pass your custom css to customize header -->
        <x-admin::datagrid
            src="{{ route('admin.deliveryagents.index') }}"
            :isMultiRow="true"
        >
            @php
                $hasPermission = bouncer()->hasPermission('delivery.deliveryAgent.edit') || bouncer()->hasPermission('delivery.deliveryAgent.delete');
            @endphp
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
                    <div class="row grid grid-cols-[2fr_1fr_1fr] grid-rows-1 items-center border-b px-4 py-2.5 dark:border-gray-800">
                        <div
                            class="flex select-none items-center gap-2.5"
                            v-for="(columnGroup, index) in [['full_name', 'email'], ['status', 'phone']]"
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
                                        :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
                                        @change="selectAll"
                                    >

                                    <span
                                        class="icon-uncheckbox cursor-pointer rounded-md text-2xl"
                                        :class="[
                                        applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-checked peer-checked:active-checkbox' : (
                                            applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-checkbox-partial peer-checked:active-checkbox' : ''
                                        ),
                                    ]"
                                    >
                                </span>
                                </label>
                            @endif
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
                        class="row grid grid-cols-[minmax(150px,_2fr)_1fr_1fr] border-b px-4 py-2.5 transition-all hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950"
                        v-for="record in available.records"
                    >
                        <div class="flex gap-2.5">
                            @if ($hasPermission)
                                <input
                                    type="checkbox"
                                    :name="`mass_action_select_record_${record.delivery_agents_id}`"
                                    :id="`mass_action_select_record_${record.delivery_agents_id}`"
                                    :value="record.customer_id"
                                    class="peer hidden"
                                    v-model="applied.massActions.indices"
                                    @change="setCurrentSelectionMode"
                                >

                                <label
                                    class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:active-checkbox"
                                    :for="`mass_action_select_record_${record.delivery_agents_id}`"
                                >
                                </label>
                            @endif
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
                            <div class="grid grid-cols-2 gap-2 w-full">

                                <a
{{--                                    :href="`{{ route('admin.deliveryagents.edit', '') }}/${record.delivery_agents_id}`"--}}
                                    class="w-full text-center px-4 py-2 text-sm font-medium bg-blue-500 text-white rounded-md hover:bg-blue-600"
                                >
                                    تعديل
                                </a>
                                <a
                                    :href="`{{ route('admin.deliveryagents.view', '') }}/${record.delivery_agents_id}`"
                                    class="w-full text-center px-4 py-2 text-sm font-medium bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700"
                                >
                                    عرض
                                </a>
                            </div>
                        </div>


                    </div>
                </template>

            </template>

        </x-admin::datagrid>
    </x-slot>


</x-admin::drawer>
