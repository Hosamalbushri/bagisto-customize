<v-edit-state-form
    :state="state"
    ref="ShowStateEditComponent"
    @update-state="updateState"
>
    <div class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"></div>
</v-edit-state-form>

@if (bouncer()->hasPermission('delivery.country'))
    <div
        class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"
        @click="$refs.ShowStateEditComponent.openModal()"
    >
        @lang('deliveryagent::app.country.state.edit.edit-btn')
    </div>
@endif

@pushOnce('scripts')

    <script
        type="text/x-template"
        id="v-state-form-template"
    >
        <div id="country-form">
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit, reset, setErrors }"
                as="div"

            >
                <form
                    @submit="handleSubmit($event, edit)"
                    ref="StateEditForm"

                >
                    <x-admin::modal ref="modal" ref="StateEditModal">
                        <x-slot:header>
                            @lang('deliveryagent::app.country.edit.title')
                            </x-slot>

                            <x-slot:content>

                                <x-admin::form.control-group  class="mb-2.5 w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('deliveryagent::app.country.state.edit.name')
                                    </x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="default_name"
                                        id="default_name"
                                        ::value="state.default_name"
                                        rules="required"
                                        :label="trans('deliveryagent::app.country.state.edit.name')"
                                        :placeholder="trans('deliveryagent::app.country.state.edit.name')"
                                    />
                                    <x-admin::form.control-group.error control-name="default_name" />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-2.5 w-full">
                                    <x-admin::form.control-group.label >
                                        @lang('deliveryagent::app.country.state.edit.code')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="code"
                                        name="code"
                                        ::value="state.code"
                                        disabled
                                        :label="trans('deliveryagent::app.country.state.edit.code')"
                                        :placeholder="trans('deliveryagent::app.country.state.edit.code')"
                                    />
                                    <x-admin::form.control-group.error control-name="code" />
                                </x-admin::form.control-group>



                                </x-slot>

                                <x-slot:footer>
                                    <x-admin::button
                                        button-type="submit"
                                        class="primary-button"
                                        :title="trans('deliveryagent::app.country.state.edit.edit-btn')"
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
        app.component('v-edit-state-form', {
            template: '#v-state-form-template',
            props: ['state'],
            emits: ['update-state'],

            data() {
                return {
                    isLoading: false,
                };
            },

            methods: {
                openModal() {
                    this.$refs.StateEditModal.open();
                },

                edit(params, { resetForm, setErrors }) {
                    this.isLoading = true;
                    let formData = new FormData(this.$refs.StateEditForm);
                    formData.append('_method', 'put');
                    this.$axios.post("{{ route('admin.states.update',$state -> id) }}", formData)
                        .then((response) => {

                            this.$refs.StateEditModal.close();
                            this.$emit('update-state', response.data.data);
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
