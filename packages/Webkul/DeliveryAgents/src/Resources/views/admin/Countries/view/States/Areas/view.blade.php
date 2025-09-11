<x-admin::layouts>
    <x-slot:title>
        @php
            $label = trans('deliveryAgent::app.country.state.area.view.title');
        @endphp

        {{ isset($Area) && !empty($Area->area_name) ? ($label . ': ' . $Area->area_name) : $label }}
</x-slot>

<div class="flex items-center justify-between mb-4">
<p class="text-xl font-bold text-gray-800 dark:text-white">
    {{ isset($Area) && !empty($Area->area_name) ? ($label . ': ' . $Area->area_name) : $label }}
</p>

@if (bouncer()->hasPermission('delivery.deliveryAgent.create'))
    <div class="flex items-center gap-x-2.5">
        <!-- Back Button -->
        <a
            href="{{ route('admin.states.edit',$Area->country_state_id) }}"
            class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
        >
            @lang('deliveryAgent::app.country.state.view.back-btn')
        </a>

        @include('DeliveryAgents::admin.Countries.view.States.Areas.add-deliveryAgents-to-area')
            </div>
        @endif
    </div>

    <x-admin::datagrid
        src="{{ route('admin.area.view',['id'=>$Area->id,'mode' => 'in']) }}"
        ref="deliveryAgentDatagrid"
        :isMultiRow="true"
    >
        @php
            $hasPermission = bouncer()->hasPermission('delivery.deliveryAgent.range.delete');
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
                        v-for="(columnGroup, index) in [['full_name', 'email', 'phone'], ['status', 'gender', 'delivery_agents_id'], ['range_count', 'order_count', 'address_count']]"
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

                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ record.phone ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        @php
                            $activeLabel = __('deliveryAgent::app.deliveryAgent.dataGrid.active');
                            $inactiveLabel = __('deliveryAgent::app.deliveryAgent.dataGrid.inactive');
                        @endphp
                        <div class="flex gap-1.5">
                            <p v-html="record.status"></p>
                        </div>

                        <p class="text-gray-600 dark:text-gray-300">
                            @{{ record.gender ?? 'N/A' }}
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @{{ "@lang('deliveryAgent::app.deliveryAgent.dataGrid.id-value')".replace(':id', record.delivery_agents_id) }}
                        </p>

                    </div>
                    <div class="flex items-center justify-between gap-x-4">
                        <div class="flex flex-col gap-1.5">
                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('deliveryAgent::app.deliveryAgent.dataGrid.range')".replace(':range', record.range_count) }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('deliveryAgent::app.deliveryAgent.dataGrid.order')".replace(':order', record.order_count) }}
                            </p>

                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                {{--                                @{{ record.range_count }}--}}
                            </p>
                        </div>

                        <div class="flex items-center">
                            <a
                                class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                                :href=`{{ route('admin.deliveryAgents.view', '') }}/${record.delivery_agents_id}`
                            >
                            </a>
                        </div>

                    </div>


                </div>
            </template>

        </template>


    </x-admin::datagrid>
</x-admin::layouts>
