@if (bouncer()->hasPermission('delivery.countries.states.create'))
    <v-create-state-form
        :country="country"
        @state-created="onStateCreated"
    ></v-create-state-form>

@endif
@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-state-form-template"
    >
        <button
            class="inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-blue-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
            @click="$refs.StateCreateModal.open()"
        >
            <span class="acma-icon-plus3"></span>

            @lang('deliveryAgent::app.country.view.states.create-btn')
        </button>
        <div id="state-form">
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit, reset, setErrors }"
                as="div"

            >
                <form @submit="handleSubmit($event, create)" >
                    <x-admin::modal ref="modal" ref="StateCreateModal">
                        <x-slot:header>
                            @lang('deliveryAgent::app.country.state.create.title')
                        </x-slot:header>
                            <x-slot:content>
                                <x-admin::form.control-group  class="mb-2.5 w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('deliveryAgent::app.country.state.create.name')
                                    </x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="default_name"
                                        id="default_name"
                                        rules="required"
                                        :label="trans('deliveryAgent::app.country.state.create.name')"
                                        :placeholder="trans('deliveryAgent::app.country.state.create.name')"
                                    />
                                    <x-admin::form.control-group.error control-name="default_name" />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-2.5 w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('deliveryAgent::app.country.state.create.code')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="code"
                                        name="code"
                                        rules="required"
                                        :label="trans('deliveryAgent::app.country.state.create.code')"
                                        :placeholder="trans('deliveryAgent::app.country.state.create.code')"
                                    />
                                    <x-admin::form.control-group.error control-name="code" />
                                </x-admin::form.control-group>
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="country_id"
                                    v-model="country.id"
                                />

                                <!-- حقل country_code المخفي -->
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="country_code"
                                    v-model="country.code"
                                />
                            </x-slot:content>

                                <x-slot:footer>
                                    <x-admin::button
                                        button-type="submit"
                                        class="primary-button"
                                        :title="trans('deliveryAgent::app.country.state.create.create-btn')"
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
        app.component('v-create-state-form', {
            template: '#v-state-form-template',
            props: ['country'],
            emits: ['state-created'],


            data() {
                return {
                    isLoading: false,
                };
            },

            methods: {
                openModal() {
                    this.$refs.StateCreateModal.open();
                },

                create(params, { resetForm, setErrors }) {
                    this.isLoading = true;

                    this.$axios.post("{{ route('admin.states.store') }}", params)
                        .then((response) => {

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            this.$refs.StateCreateModal.close();
                            this.$emit('state-created', response.data.data);
                            resetForm();
                            this.isLoading = false;

                        })

                        .catch((error) => {
                            this.isLoading = false;

                            if (error.response?.status === 422) {
                                setErrors(error.response.data.errors);

                            }
                        });
                }

            }
        });

    </script>
@endPushOnce
