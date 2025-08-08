<v-edit-country-form
    :country="country"
    ref="ShowCountryEditComponent"
    @update-country="updateCountry"
>
    <div class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"></div>
</v-edit-country-form>

@if (bouncer()->hasPermission('delivery.countries.country.edit'))
    <div
        class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"
        @click="$refs.ShowCountryEditComponent.openModal()"
    >
        @lang('deliveryagent::app.country.edit.edit-btn')
    </div>
@endif

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
                <form
                    @submit="handleSubmit($event, edit)"
                    ref="CountryEditForm"
                >
                    <x-admin::modal ref="modal" ref="CountryEditModal">
                        <x-slot:header>
                            @lang('deliveryagent::app.country.edit.title')
                            </x-slot>

                            <x-slot:content>

                                <x-admin::form.control-group  class="mb-2.5 w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('deliveryagent::app.country.edit.name')
                                    </x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        id="name"
                                        ::value="country.name"
                                        rules="required"
                                        :label="trans('deliveryagent::app.country.edit.name')"
                                        :placeholder="trans('deliveryagent::app.country.edit.name')"
                                    />
                                    <x-admin::form.control-group.error control-name="name" />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-2.5 w-full">
                                    <x-admin::form.control-group.label >
                                        @lang('deliveryagent::app.country.edit.code')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="code"
                                        name="code"
                                        ::value="country.code"
                                        disabled
                                        :label="trans('deliveryagent::app.country.edit.code')"
                                        :placeholder="trans('deliveryagent::app.country.edit.code')"
                                    />
                                    <x-admin::form.control-group.error control-name="code" />
                                </x-admin::form.control-group>



                                </x-slot>

                                <x-slot:footer>
                                    <x-admin::button
                                        button-type="submit"
                                        class="primary-button"
                                        :title="trans('deliveryagent::app.country.edit.edit-btn')"
                                        ::loading="isLoading"
                                        ::disabled="isLoading"
                                    />
                                    </x-slot>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>

    </script>


    <script type="module">
        app.component('v-edit-country-form', {
            template: '#v-country-form-template',
            props: ['country'],
            emits: ['update-country'],

            data() {
                return {
                    isLoading: false,
                };
            },

            methods: {
                openModal() {
                    this.$refs.CountryEditModal.open();
                },

                edit(params, { resetForm, setErrors }) {
                    this.isLoading = true;
                    let formData = new FormData(this.$refs.CountryEditForm);
                    formData.append('_method', 'put');
                    this.$axios.post("{{ route('admin.country.update',$country -> id) }}", formData)
                        .then((response) => {

                            this.$refs.CountryEditModal.close();
                            this.$emit('update-country', response.data.data);
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

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
