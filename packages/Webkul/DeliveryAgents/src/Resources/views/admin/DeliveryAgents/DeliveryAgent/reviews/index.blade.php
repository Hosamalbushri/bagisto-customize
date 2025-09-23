<x-admin::tabs.item
    title="{{ __('deliveryAgent::app.review.index.tab.title') }}"
>
    {!! view_render_event('bagisto.admin.delivery_agents.reviews.edit.before') !!}
    <v-delivery-agent-review-edit-drawer></v-delivery-agent-review-edit-drawer>
    {!! view_render_event('bagisto.admin.delivery_agents.reviews.list.before') !!}

</x-admin::tabs.item>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-delivery-agent-review-edit-drawer-template"
        >



             <x-admin::datagrid
                 :src="route('admin.reviews.index')"
                 :isMultiRow="true"
                 ref="review_data"
             >
                @php
                    $hasPermission = bouncer()->hasPermission('delivery_agents.reviews.edit') || bouncer()->hasPermission('delivery_agents.reviews.delete');
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
                        <div class="row grid grid-cols-[2fr_1fr_minmax(150px,_4fr)_0.5fr] grid-rows-1 items-center border-b px-4 py-2.5 dark:border-gray-800">
                            <div
                                class="flex items-center gap-2.5"
                                v-for="(columnGroup, index) in [['customer_name', 'agent_name', 'status'], ['rating', 'created_at', 'id'], ['order_increment_id', 'comment']]"
                            >
                                @if ($hasPermission)
                                    <label
                                        class="flex w-max cursor-pointer select-none items-center gap-1"
                                        for="mass_action_select_all_records"
                                        v-if="! index"
                                    >
                                        <input
                                            type="checkbox"
                                            id="mass_action_select_all_records"
                                            class="peer hidden"
                                            name="mass_action_select_all_records"
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

                                <!-- Customer Name, Agent Name, Status -->
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
                            class="row grid grid-cols-[2fr_1fr_minmax(150px,_4fr)_0.5fr] border-b px-4 py-2.5 transition-all hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950"
                            v-for="record in available.records"
                        >
                            <!-- Customer Name, Agent Name, Status -->
                            <div class="flex gap-2.5">
                                @if ($hasPermission)
                                    <input
                                        type="checkbox"
                                        :id="`mass_action_select_record_${record.id}`"
                                        class="peer hidden"
                                        :name="`mass_action_select_record_${record.id}`"
                                        :value="record.id"
                                        v-model="applied.massActions.indices"
                                        @change="setCurrentSelectionMode"
                                    >

                                    <label
                                        class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:active-checkbox"
                                        :for="`mass_action_select_record_${record.id}`"
                                    ></label>
                                @endif

                                <div class="flex flex-col gap-1.5">
                                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                                        @{{ record.customer_name }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ record.agent_name }}
                                    </p>

                                    <p v-html="record.status"></p>
                                </div>
                            </div>

                            <!-- Rating, Date, Id Section -->
                            <div class="flex flex-col gap-1.5">
                                <div class="flex">
                                    <x-admin::star-rating
                                        :is-editable="false"
                                        ::value="record.rating"
                                    />
                                </div>

                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ record.created_at }}
                                </p>

                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                >
                                    @{{ "@lang('deliveryAgent::app.review.index.datagrid.review-id')".replace(':review_id', record.id) }}
                                </p>
                            </div>

                            <!-- Order ID, Comment -->
                            <div class="flex flex-col gap-1.5">
                                <p class="text-base font-semibold text-gray-800 dark:text-white">
                                    @{{ record.order_increment_id }}
                                </p>

                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ record.comment }}
                                </p>
                            </div>

                            <div class="flex place-content-end items-center gap-1.5 self-center">
                                <!-- Review Delete Button -->
                                <a @click="performAction(record.actions.find(action => action.index === 'delete'))">
                                    <span
                                        :class="record.actions.find(action => action.index === 'delete')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                                    >
                                    </span>
                                </a>

                                <!-- View Button -->
                                <a
                                    v-if="record.actions.find(action => action.index === 'edit')"
                                    @click="edit(record.actions.find(action => action.index === 'edit')?.url)"
                                >
                                    <span class="icon-sort-right rtl:icon-sort-left cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"></span>
                                </a>
                            </div>
                        </div>
                    </template>
                </template>
            </x-admin::datagrid>

            {!! view_render_event('bagisto.admin.delivery_agents.reviews.list.after') !!}

            <!-- Drawer content -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form
                        @submit="handleSubmit($event, update)"
                        ref="reviewCreateForm"
                    >
                        <x-admin::drawer ref="review">
                            <!-- Drawer Header -->
                            <x-slot:header>
                                <div class="flex items-center justify-between">
                                    <p class="text-xl font-medium dark:text-white">
                                        @lang('deliveryAgent::app.review.index.edit.title')
                                    </p>

                                    <button class="primary-button ltr:mr-11 rtl:ml-11">
                                        @lang('deliveryAgent::app.review.index.edit.save-btn')
                                    </button>
                                </div>
                            </x-slot>

                            <!-- Drawer Content -->
                            <x-slot:content>
                                <div class="flex flex-col gap-6 px-4 py-4">
                                    <!-- Basic Information: two columns -->
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="space-y-1">
                                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                                @lang('deliveryAgent::app.review.index.edit.customer')
                                            </label>
                                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                                @{{ review.customer_name || 'N/A' }}
                                            </p>
                                        </div>

                                        <div class="space-y-1">
                                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                                @lang('deliveryAgent::app.review.index.edit.agent')
                                            </label>
                                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                                @{{ review.agent_name || 'N/A' }}
                                            </p>
                                        </div>

                                        <div class="space-y-1">
                                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                                @lang('deliveryAgent::app.review.index.edit.order_id')
                                            </label>
                                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                                @{{ review.order_increment_id || 'N/A' }}
                                            </p>
                                        </div>

                                        <div class="space-y-1">
                                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                                @lang('deliveryAgent::app.review.index.edit.date')
                                            </label>
                                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                                @{{ review.created_at }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-200 dark:border-gray-700"></div>

                                    <!-- Status and Rating: two columns -->
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="space-y-4">
                                            <x-admin::form.control-group.control
                                                type="hidden"
                                                name="id"
                                                rules="required"
                                                ::value="review.id"
                                            />

                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label class="required">
                                                    @lang('deliveryAgent::app.review.index.edit.status')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="status"
                                                    rules="required"
                                                    ::value="review.status"
                                                >
                                                    <option value="approved">
                                                        @lang('deliveryAgent::app.review.index.edit.approved')
                                                    </option>

                                                    <option value="disapproved">
                                                        @lang('deliveryAgent::app.review.index.edit.disapproved')
                                                    </option>

                                                    <option value="pending">
                                                        @lang('deliveryAgent::app.review.index.edit.pending')
                                                    </option>
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error control-name="status" />
                                            </x-admin::form.control-group>
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                                @lang('deliveryAgent::app.review.index.edit.rating')
                                            </label>
                                            <div class="flex items-center gap-2">
                                                <x-admin::star-rating
                                                    :is-editable="false"
                                                    ::value="review.rating"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-200 dark:border-gray-700"></div>

                                    <!-- Comment (full width) -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            @lang('deliveryAgent::app.review.index.edit.comment')
                                        </label>
                                        <div class="rounded-md border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800">
                                            <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-line">
                                                @{{ review.comment || 'لا يوجد تعليق' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </x-slot>
                        </x-admin::drawer>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-delivery-agent-review-edit-drawer', {
                template: '#v-delivery-agent-review-edit-drawer-template',

                data() {
                    return {
                        review: {},
                    }
                },

                methods: {
                     edit(url) {
                         this.$axios.get(url)
                             .then((response) => {
                                 this.$refs.review.open();

                                 this.review = response.data.data;
                             })
                             .catch(error => {
                                 if (error.response.status == 422) {
                                     setErrors(error.response.data.errors);
                                 }
                             });
                     },

                     update(params) {
                         let formData = new FormData(this.$refs.reviewCreateForm);

                         formData.append('_method', 'put');

                         this.$axios.post(`{{ route('admin.review.update', '') }}/${params.id}`, formData)
                             .then((response) => {
                                 this.$refs.review.close();

                                 this.$refs.review_data.get();

                                 this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                             })
                             .catch(error => {
                                 if (error.response.status == 422) {
                                     setErrors(error.response.data.errors);
                                 }
                             });
                     },
                }
            })
        </script>
    @endPushOnce
