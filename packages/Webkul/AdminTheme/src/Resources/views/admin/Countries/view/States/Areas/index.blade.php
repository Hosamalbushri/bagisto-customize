<v-area
    :state="state"
>
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-x-2.5">
            <!-- Create Button -->
            @if (bouncer()->hasPermission('delivery.countries.states.area.create'))
                <button
                    type="button"
                    class="primary-button"
                >
                    @lang('adminTheme::app.country.state.view.create-area-btn')
                </button>
            @endif
        </div>
    </div>

    <x-admin::shimmer.datagrid />

</v-area>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-areas-template"
    >
        <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-x-2.5">
                    @if (bouncer()->hasPermission('delivery.countries.states.area.create'))
                        <button
                            type="button"
                            class="inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-blue-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
                            @click="resetForm();$refs.areaUpdateOrCreateModal.open()"
                        >

                            @lang('adminTheme::app.country.state.area.create.title')
                        </button>
                    @endif
                </div>
            </div>
        <x-admin::datagrid
            src="{{ route('admin.area.index', ['country_state_id' => $state->id]) }}"
            ref="areaDatagrid"
        >
            @php
                $hasPermission = bouncer()->hasPermission('delivery.countries.states.area.edit') || bouncer()->hasPermission('delivery.countries.states.area.delete');
            @endphp
            <template #body="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                <template v-if="isLoading">
                    <x-admin::shimmer.datagrid.table.body />
                </template>
                <template v-else>
                    <div
                        v-for="record in available.records"
                        class="row grid items-center gap-2.5 border-b px-4 py-4 text-gray-600 transition-all hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950"
                        :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                    >
                        <p>@{{ record.state_areas_id }}</p>
                        <p>@{{ record.area_name }}</p>
                        <p>@{{ record.delivery_agents_count }}</p>
                            <!-- Actions -->
                        <div class="flex justify-end">
                            <template v-if="hasPermission">
                                <a @click="performAction(record.actions.find(action => action.index === 'view'))">
                                    <span
                                        :class="record.actions.find(action => action.index === 'view')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>
                                <a @click="editModal(record.actions.find(action => action.index === 'edit')?.url)">
                                    <span
                                        :class="record.actions.find(action => action.index === 'edit')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>
                                <a @click="performAction(record.actions.find(action => action.index === 'delete'))">
                                    <span
                                        :class="record.actions.find(action => action.index === 'delete')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>

                            </template>

                        </div>

                    </div>
                </template>

                </template>

        </x-admin::datagrid>
        </div>

        <!-- Modal Form -->
        <div>
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form
                    @submit="handleSubmit($event,updateOrCreate)"
                    ref="areaCreateForm"
                >
                    <x-admin::modal ref="areaUpdateOrCreateModal">
                        <!-- Modal Header -->
                        <x-slot:header>

                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-if="isUpdating"
                            >
                                @lang('adminTheme::app.country.state.area.edit.title')
                            </p>

                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-else
                            >
                                @lang('adminTheme::app.country.state.area.create.title')
                            </p>
                        </x-slot:header>
                            <x-slot:content>


                            <x-admin::form.control-group>
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                    v-model="data.area.id"
                                />
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="country_state_id"
                                    v-model="state.id"
                                />

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="state_code"
                                    v-model="state.code"
                                />
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="country_code"
                                    v-model="state.country_code"
                                />
                                <x-admin::form.control-group  class="mb-2.5 w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('adminTheme::app.country.state.area.edit.name')
                                    </x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="area_name"
                                        id="area_name"
                                        v-model="data.area.area_name"
                                        rules="required"
                                        :label="trans('adminTheme::app.country.state.area.edit.name')"
                                        :placeholder="trans('adminTheme::app.country.state.area.edit.name')"
                                    />
                                    <x-admin::form.control-group.error control-name="area_name" />
                                </x-admin::form.control-group>
                            </x-admin::form.control-group>
                            </x-slot:content>
                            <x-slot:footer>
                                <x-admin::button
                                    button-type="submit"
                                    class="primary-button"
                                    :title="trans('adminTheme::app.country.state.area.create.save-btn')"
                                    ::loading="isLoading"
                                    ::disabled="isLoading"
                                />
                            </x-slot:footer>

                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>

    </script>
    <script type="module">
        app.component('v-area', {
            template: '#v-areas-template',
            props: ['state'],


            data() {
                return {
                    isUpdating: false,
                    data: {
                        area: {},
                    },
                    isLoading: false,
                }
            },
            computed: {
                gridsCount() {
                    let count = 3;

                    if (this.hasPermission) {
                        count++;
                    }

                    return count;
                },

                hasPermission() {
                    return @json($hasPermission);
                }
            },
            methods: {
                updateOrCreate(params, { resetForm, setErrors  }) {
                    this.isLoading = true;
                    let formData = new FormData(this.$refs.areaCreateForm);
                    if (params.id) {
                        formData.append('_method', 'put');
                    }
                    this.$axios.post(params.id ? "{{ route('admin.area.update') }}" : "{{ route('admin.area.store') }}", formData)
                        .then((response) => {
                            this.isLoading = false;

                            this.$refs.areaUpdateOrCreateModal.close();

                            this.$refs.areaDatagrid.get();

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            resetForm();
                        })
                        .catch(error => {
                            this.isLoading = false;

                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },

                editModal(url) {
                    this.isUpdating = true;

                    this.$axios.get(url)
                        .then((response) => {
                            this.data = {
                                ...response.data,
                                area: {
                                    ...response.data.area,
                                },
                            };

                            this.$refs.modalForm.setValues(response.data.area);

                            this.$refs.areaUpdateOrCreateModal.toggle();
                        })
                        .catch(error => this.$emitter.emit('add-flash', {
                            type: 'error', message: error.response.data.message
                        }));
                },
                resetForm() {
                    this.isUpdating = false;

                    this.data = {
                        area: {},
                    };
                },

            }
        })
    </script>
@endpushonce

