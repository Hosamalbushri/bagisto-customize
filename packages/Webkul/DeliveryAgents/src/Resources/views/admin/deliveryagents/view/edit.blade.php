<v-edit-delivery-agent-form
    :deliveryAgent="deliveryagent"
    ref="ShowDeliveryAgentEditComponent"
    @update-delivery-agent="updateDeliveyAgent"
>
    <div class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"></div>
</v-edit-delivery-agent-form>

@if (bouncer()->hasPermission('delivery.deliveryAgent.edit'))
    <div
        class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"
        @click="$refs.ShowDeliveryAgentEditComponent.openModal()"
    >
        @lang('deliveryagent::app.deliveryagents.view.edit-btn')
    </div>
@endif


@pushOnce('scripts')

    <script
        type="text/x-template"
        id="v-delivery-agent-template"
    >

        <div id="delivery-agent-form">
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit, reset, setErrors }"
                as="div"
            >
                <form @submit="handleSubmit($event, edit)"
                      ref="deliveryAgentEditForm"
                >

                    <x-admin::modal ref="modal" ref="DeliveryAgentEditModal">


                        <x-slot:header>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">
                                @lang('deliveryagent::app.deliveryagents.edit.title')
                            </p>
                            </x-slot>


                            <x-slot:content>

                                <div class="flex gap-4 max-sm:flex-wrap">
                                    <!-- First Name -->
                                    <x-admin::form.control-group  class="mb-2.5 w-full">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('deliveryagent::app.deliveryagents.edit.first-name')
                                        </x-admin::form.control-group.label>
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="first_name"
                                            id="first_name"
                                            ::value="deliveryAgent.first_name"
                                            rules="required"
                                            :label="trans('deliveryagent::app.deliveryagents.edit.first-name')"
                                            :placeholder="trans('deliveryagent::app.deliveryagents.edit.first-name')"
                                        />
                                        <x-admin::form.control-group.error control-name="first_name" />
                                    </x-admin::form.control-group>

                                    <!-- Last Name -->
                                    <x-admin::form.control-group class="mb-2.5 w-full">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('deliveryagent::app.deliveryagents.edit.last-name')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            id="last_name"
                                            name="last_name"
                                            ::value="deliveryAgent.last_name"
                                            rules="required"
                                            :label="trans('deliveryagent::app.deliveryagents.edit.last-name')"
                                            :placeholder="trans('deliveryagent::app.deliveryagents.edit.last-name')"
                                        />

                                        <x-admin::form.control-group.error control-name="last_name" />
                                    </x-admin::form.control-group>
                                </div>

                                <!-- Email -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('deliveryagent::app.deliveryagents.edit.email')
                                    </x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="email"
                                        name="email"
                                        ::value="deliveryAgent.email"
                                        id="email"
                                        rules="required|email"
                                        :label="trans('deliveryagent::app.deliveryagents.edit.email')"
{{--                                        placeholder= "email@example.com"--}}
                                    />
                                    <x-admin::form.control-group.error control-name="email" />
                                </x-admin::form.control-group>

                                <div class="flex gap-4 max-sm:flex-wrap">
                                <!-- كلمة المرور -->
                                <x-admin::form.control-group class="mb-2.5 w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('deliveryagent::app.deliveryagents.edit.password')
                                    </x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="password"
                                        name="password"
                                        id="password"
                                        :label="trans('deliveryagent::app.deliveryagents.edit.password')"
                                        :placeholder="trans('deliveryagent::app.deliveryagents.edit.password')"
                                    />
                                    <x-admin::form.control-group.error control-name="password" />
                                </x-admin::form.control-group>


                                <!-- تأكيد كلمة المرور -->
                                <x-admin::form.control-group class="mb-2.5 w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('deliveryagent::app.deliveryagents.create.confirm-password')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        :label="trans('deliveryagent::app.deliveryagents.create.confirm-password')"
                                        :placeholder="trans('deliveryagent::app.deliveryagents.create.confirm-password')"
                                    />
                                    <x-admin::form.control-group.error control-name="password_confirmation" />
                                </x-admin::form.control-group>
                                </div>

                                <div class="flex gap-4 max-sm:flex-wrap">

                                    <!-- Contact Number -->
                                    <x-admin::form.control-group class="mb-2.5 w-full">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('deliveryagent::app.deliveryagents.edit.phone')
                                        </x-admin::form.control-group.label>
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="phone"
                                            ::value="deliveryAgent.phone"
                                            id="phone"
                                            rules="required|phone"
                                            :label="trans('deliveryagent::app.deliveryagents.edit.phone')"
                                            :placeholder="trans('deliveryagent::app.deliveryagents.edit.phone')"
                                        />
                                        <x-admin::form.control-group.error control-name="phone" />
                                    </x-admin::form.control-group>


                                    <x-admin::form.control-group class="mb-2.5 w-full">
                                        <x-admin::form.control-group.label>
                                            @lang('deliveryagent::app.deliveryagents.edit.date-of-birth')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="date"
                                            id="dob"
                                            name="date_of_birth"
                                            ::value="deliveryAgent.date_of_birth"
                                            :label="trans('deliveryagent::app.deliveryagents.edit.date-of-birth')"
                                            :placeholder="trans('deliveryagent::app.deliveryagents.edit.date-of-birth')"
                                        />

                                        <x-admin::form.control-group.error control-name="date_of_birth" />
                                    </x-admin::form.control-group>

                                </div>

                                <div class="flex gap-4 max-sm:flex-wrap">
                                    <!-- Gender -->
                                    <x-admin::form.control-group class="mb-2.5 w-full">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('deliveryagent::app.deliveryagents.edit.gender')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            id="gender"
                                            name="gender"
                                            ::value="deliveryAgent.gender"
                                            rules="required"
                                            :label="trans('deliveryagent::app.deliveryagents.edit.gender')"
                                        >
                                            <option value="">
                                                @lang('deliveryagent::app.deliveryagents.edit.select-gender')
                                            </option>

                                            <option value="Male">
                                                @lang('deliveryagent::app.deliveryagents.edit.male')
                                            </option>

                                            <option value="Female">
                                                @lang('deliveryagent::app.deliveryagents.edit.female')
                                            </option>

                                            <option value="Other">
                                                @lang('deliveryagent::app.deliveryagents.edit.other')
                                            </option>
                                        </x-admin::form.control-group.control>
                                        <x-admin::form.control-group.error control-name="gender" />
                                    </x-admin::form.control-group>
                                </div>

                                <div class="flex gap-60 max-sm:flex-wrap">
                                    <!-- Customer Status -->


                                                    <!-- الحالة -->
                                                    <x-admin::form.control-group class="mb-2.5 w-full">
                                                        <x-admin::form.control-group.label class="required">
                                                            @lang('deliveryagent::app.deliveryagents.create.status')
                                                        </x-admin::form.control-group.label>
                                                        <x-admin::form.control-group.control
                                                            type="select"
                                                            name="status"
                                                            id="status"
                                                            ::value="deliveryAgent.status"
                                                            rules="required"
                                                            :label="trans('deliveryagent::app.deliveryagents.create.status')"
                                                        >
                                                            <option value="">@lang('deliveryagent::app.deliveryagents.create.select-status')</option>
                                                            <option value="1">@lang('deliveryagent::app.deliveryagents.create.active')</option>
                                                            <option value="0">@lang('deliveryagent::app.deliveryagents.create.inactive')</option>
                                                        </x-admin::form.control-group.control>
                                                        <x-admin::form.control-group.error control-name="status" />
                                                    </x-admin::form.control-group>

                                </div>


                                </x-slot>

                                <x-slot:footer>
                                    <x-admin::button
                                        button-type="submit"
                                        class="primary-button"
                                        :title="trans('deliveryagent::app.deliveryagents.edit.save-btn')"
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
        app.component('v-edit-delivery-agent-form', {
            template: '#v-delivery-agent-template',
            props: ['deliveryAgent'],
            emits: ['update-delivery-agent'],


            data() {
                return {
                    isLoading: false,
                };
            },

            methods: {
                openModal() {
                    this.$refs.DeliveryAgentEditModal.open();
                },

                edit(params, { resetForm, setErrors }) {
                    this.isLoading = true;
                    let formData = new FormData(this.$refs.deliveryAgentEditForm);
                    formData.append('_method', 'put');
                    this.$axios.post("{{ route('admin.deliveryagents.update',$deliveryAgent -> id) }}", formData)
                        .then((response) => {
                            this.$refs.DeliveryAgentEditModal.close();
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            this.$emit('update-delivery-agent', response.data.data);
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
