@pushOnce('scripts')

    <script
        type="text/x-template"
        id="v-country-form-template"
    >
        <div id="country-form">
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit, reset, setErrors }"
                as="div"

            >
                <form @submit="handleSubmit($event, create)" >
                    <x-admin::modal ref="modal" ref="CountryCreateModal">


                        <x-slot:header>
                            @lang('deliveryAgent::app.country.create.title')
                            </x-slot>

                            <x-slot:content>

                                    <x-admin::form.control-group  class="mb-2.5 w-full">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('deliveryAgent::app.country.create.name')
                                        </x-admin::form.control-group.label>
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="name"
                                            id="name"
                                            rules="required"
                                            :label="trans('deliveryAgent::app.country.create.name')"
                                            :placeholder="trans('deliveryAgent::app.country.create.name')"
                                        />
                                        <x-admin::form.control-group.error control-name="name" />
                                    </x-admin::form.control-group>

                                    <!-- Last Name -->
                                    <x-admin::form.control-group class="mb-2.5 w-full">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('deliveryAgent::app.country.create.code')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            id="code"
                                            name="code"
                                            rules="required"
                                            :label="trans('deliveryAgent::app.country.create.code')"
                                            :placeholder="trans('deliveryAgent::app.country.create.code')"
                                        />
                                        <x-admin::form.control-group.error control-name="code" />
                                    </x-admin::form.control-group>
                            </x-slot:content>

                                <x-slot:footer>
                                    <x-admin::button
                                        button-type="submit"
                                        class="primary-button"
                                        :title="trans('deliveryAgent::app.country.create.create-btn')"
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
        app.component('v-create-country-form', {
            template: '#v-country-form-template',

            data() {
                return {
                    isLoading: false,
                };
            },

            methods: {
                openModal() {
                    this.$refs.CountryCreateModal.open();
                },

                create(params, { resetForm, setErrors }) {
                    this.isLoading = true;

                    this.$axios.post("{{ route('admin.country.store') }}", params)
                        .then((response) => {

                            const countryId = response.data.data.id;
                            const viewUrl = "{{ route('admin.country.edit', ':id') }}".replace(':id', countryId);
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            setTimeout(() => {
                                window.location.href = viewUrl;
                            }, 1000);


                            // this.$refs.CountryCreateModal.close();
                            // this.$emit('country-created', response.data.data);
                            //
                            // this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            //
                            // resetForm();
                            // this.isLoading = false;

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
