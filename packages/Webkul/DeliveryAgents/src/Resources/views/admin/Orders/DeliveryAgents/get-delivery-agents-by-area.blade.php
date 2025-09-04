
<div class="box-shadow rounded bg-white p-4 dark:bg-gray-900 max-h-[70vh] overflow-y-auto">

    <x-admin::datagrid
        src="{{ route('admin.deliveryagents.order.select-delivery-agent') }}?area_id={{ $order->shipping_address->state_area_id }}">
        @php
            $hasPermission = bouncer()->hasPermission('delivery.deliveryAgent.edit') || bouncer()->hasPermission('delivery.deliveryAgent.delete') || bouncer()->hasPermission('delivery.deliveryAgent.order.assign-delivery-agent');
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
                <x-admin::shimmer.datagrid.table.head/>
            </template>
            <template v-else>
                <div
                    class="row grid grid-cols-[2fr_1fr_1fr] grid-rows-1 items-center border-b border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
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
                                    :checked="['all', 'partial'].includes(applied.massActions.meta.primary_column)"
                                    @change="selectAll"
                                >
                                <span
                                    class="icon-uncheckbox cursor-pointer rounded-md text-2xl"
                                    :class="[applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-checked peer-checked:active-checkbox' :
                                                             (applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-checkbox-partial peer-checked:active-checkbox' : ''),]"
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
                                            available.columns.find(columnTemp => columnTemp.index === column)?.sortable ? sort(available.columns.find(columnTemp => columnTemp.index === column)): {}
                                        "
                                    >
                                        @{{ available.columns.find(columnTemp => columnTemp.index === column)?.label }}
                                    </span>
                                </template>
                            </span><i class="align-text-bottom text-base text-gray-800 dark:text-white ltr:ml-1.5 rtl:mr-1.5"
                                      :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                      v-if="columnGroup.includes(applied.sort.column)"></i>
                    </div>
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

                            <p v-html="record.status"></p>

                        </div>
                        <p class="text-gray-600 dark:text-gray-300">
                            @{{ record.phone ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="flex w-full flex-col gap-1.5">
                        <div class="flex w-full justify-end gap-1">
                            @if (bouncer()->hasPermission($hasPermission))
                                <button
                                    type="button"
                                    class="acma-icon-fact_check  cursor-pointer p-1.5 text-xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                                    @click="$emitter.emit('request-assign-delivery', { orderId: {{ $order->id }}, agentId: record.delivery_agents_id })"
                                ></button>
                            @endif

                            <a
                                :href="`{{ route('admin.deliveryagents.view', '') }}/${record.delivery_agents_id}`"
                                class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-3xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                            >
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </template>

    </x-admin::datagrid>
</div>

