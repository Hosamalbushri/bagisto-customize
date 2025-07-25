@pushOnce('scripts')

    <script
    type="text/x-template"
    id="v-delivery-agent-form-template"
>
    <div id="delivery-agent-form">
        <x-admin::form
        v-slot="{ meta, errors, handleSubmit, reset, setErrors }"
        as="div"

        >
        <form @submit="handleSubmit($event, create)" >
        <x-admin::modal ref="modal" ref="DeliveryAgentCreateModal">


        <x-slot:header>
            @lang('deliveryagent::app.deliveryagents.create.title')
        </x-slot>

        <x-slot:content>

         <div class="flex gap-4 max-sm:flex-wrap">
             <!-- First Name -->
            <x-admin::form.control-group  class="mb-2.5 w-full">
            <x-admin::form.control-group.label class="required">
            @lang('deliveryagent::app.deliveryagents.create.first-name')
                </x-admin::form.control-group.label>
            <x-admin::form.control-group.control
            type="text"
            name="first_name"
            id="first_name"
            rules="required"
            :label="trans('deliveryagent::app.deliveryagents.create.first-name')"
            :placeholder="trans('deliveryagent::app.deliveryagents.create.first-name')"
            />
            <x-admin::form.control-group.error control-name="first_name" />
            </x-admin::form.control-group>

            <!-- Last Name -->
            <x-admin::form.control-group class="mb-2.5 w-full">
                <x-admin::form.control-group.label class="required">
                    @lang('deliveryagent::app.deliveryagents.create.last-name')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    id="last_name"
                    name="last_name"
                    rules="required"
                    :label="trans('deliveryagent::app.deliveryagents.create.last-name')"
                    :placeholder="trans('deliveryagent::app.deliveryagents.create.last-name')"
                />

                <x-admin::form.control-group.error control-name="last_name" />
            </x-admin::form.control-group>
    </div>

            <!-- Email -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    @lang('deliveryagent::app.deliveryagents.create.email')
                </x-admin::form.control-group.label>
                <x-admin::form.control-group.control
                    type="email"
                    name="email"
                    id="email"
                    rules="required|email"
                    :label="trans('deliveryagent::app.deliveryagents.create.email')"
{{--                    placeholder= "email@example.com"--}}
                />
                <x-admin::form.control-group.error control-name="email" />
            </x-admin::form.control-group>

            <div class="flex gap-4 max-sm:flex-wrap">
            <!-- كلمة المرور -->
            <x-admin::form.control-group class="mb-2.5 w-full">
                <x-admin::form.control-group.label class="required">
                    @lang('deliveryagent::app.deliveryagents.create.password')
                </x-admin::form.control-group.label>
                <x-admin::form.control-group.control
                    type="password"
                    name="password"
                    id="password"
                    rules="required|min:6"
                    :label="trans('deliveryagent::app.deliveryagents.create.password')"
                    :placeholder="trans('deliveryagent::app.deliveryagents.create.password')"
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
                    rules="required"
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
            @lang('deliveryagent::app.deliveryagents.create.phone')
                </x-admin::form.control-group.label>
            <x-admin::form.control-group.control
            type="text"
            name="phone"
            id="phone"
            rules="required"
            :label="trans('deliveryagent::app.deliveryagents.create.phone')"
            :placeholder="trans('deliveryagent::app.deliveryagents.create.phone')"
            />
            <x-admin::form.control-group.error control-name="phone" />
            </x-admin::form.control-group>


            <x-admin::form.control-group class="mb-2.5 w-full">
                <x-admin::form.control-group.label>
                    @lang('deliveryagent::app.deliveryagents.create.date-of-birth')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="date"
                    id="dob"
                    name="date_of_birth"
                    :label="trans('deliveryagent::app.deliveryagents.create.date-of-birth')"
                    :placeholder="trans('deliveryagent::app.deliveryagents.create.date-of-birth')"
                />

                <x-admin::form.control-group.error control-name="date_of_birth" />
            </x-admin::form.control-group>

            </div>

            <div class="flex gap-4 max-sm:flex-wrap">
                <!-- Gender -->
                <x-admin::form.control-group class="mb-2.5 w-full">
                    <x-admin::form.control-group.label class="required">
                        @lang('deliveryagent::app.deliveryagents.create.gender')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="select"
                        id="gender"
                        name="gender"
                        rules="required"
                        :label="trans('deliveryagent::app.deliveryagents.create.gender')"
                    >
                        <option value="">
                            @lang('deliveryagent::app.deliveryagents.create.select-gender')
                        </option>

                        <option value="Male">
                            @lang('deliveryagent::app.deliveryagents.create.male')
                        </option>

                        <option value="Female">
                            @lang('deliveryagent::app.deliveryagents.create.female')
                        </option>

                        <option value="Other">
                            @lang('deliveryagent::app.deliveryagents.create.other')
                        </option>
                    </x-admin::form.control-group.control>
                    <x-admin::form.control-group.error control-name="gender" />
                </x-admin::form.control-group>



{{--                <!-- الحالة -->--}}
{{--                <x-admin::form.control-group class="mb-2.5 w-full">--}}
{{--                    <x-admin::form.control-group.label class="required">--}}
{{--                        @lang('deliveryagent::app.deliveryagents.create.status')--}}
{{--                    </x-admin::form.control-group.label>--}}
{{--                    <x-admin::form.control-group.control--}}
{{--                        type="select"--}}
{{--                        name="status"--}}
{{--                        id="status"--}}
{{--                        rules="required"--}}
{{--                        :label="trans('deliveryagent::app.deliveryagents.create.status')"--}}
{{--                    >--}}
{{--                        <option value="">@lang('deliveryagent::app.deliveryagents.create.select-status')</option>--}}
{{--                        <option value="1">@lang('deliveryagent::app.deliveryagents.create.active')</option>--}}
{{--                        <option value="0">@lang('deliveryagent::app.deliveryagents.create.inactive')</option>--}}
{{--                    </x-admin::form.control-group.control>--}}
{{--                    <x-admin::form.control-group.error control-name="status" />--}}
{{--                </x-admin::form.control-group>--}}

            </div>


    </x-slot>

    <x-slot:footer>
        <x-admin::button
        button-type="submit"
        class="primary-button"
        :title="trans('deliveryagent::app.deliveryagents.create.create-btn')"
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
        app.component('v-create-delivery-agent-form', {
            template: '#v-delivery-agent-form-template',

            data() {
                return {
                    isLoading: false,
                };
            },

            methods: {
                openModal() {
                    this.$refs.DeliveryAgentCreateModal.open();
                },
                create(params, { resetForm, setErrors }) {
                    this.isLoading = true;

                    this.$axios.post("{{ route('admin.deliveryagents.store') }}", params)
                        .then((response) => {
                            this.$refs.DeliveryAgentCreateModal.close();
                            this.$emit('delivery-agent-created', response.data.data);

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            resetForm();
                            this.isLoading = false;

                        })
                        .catch((error) => {
                            this.isLoading = false;

                            if (error.response?.status === 422) {
                                setErrors(error.response.data.errors);

                                // this.$emitter.emit('add-flash', {
                                //     type: 'error',
                                //     message: 'تحقق من البيانات المدخلة.',
                                // });
                            }
                        });
                }
            }
        });

    </script>
@endPushOnce
