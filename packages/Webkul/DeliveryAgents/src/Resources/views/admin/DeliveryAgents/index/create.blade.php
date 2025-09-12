<v-create-delivery-agent-form
    @delivery-agent-created="$refs.deliveryAgentDatagrid.get()"
></v-create-delivery-agent-form>


@pushOnce('scripts')

    <script
    type="text/x-template"
    id="v-delivery-agent-form-template"
>
        @if (bouncer()->hasPermission('delivery.deliveryAgent.create'))
            <button
                class="primary-button"
                @click="$refs.DeliveryAgentCreateModal.open()"
            >
                @lang('deliveryAgent::app.deliveryAgent.create.create')
            </button>
        @endif
    <div id="delivery-agent-form">
        <x-admin::form
        v-slot="{ meta, errors, handleSubmit, reset, setErrors }"
        as="div"

        >
        <form @submit="handleSubmit($event, create)" >
        <x-admin::modal ref="modal" ref="DeliveryAgentCreateModal">


        <x-slot:header>
            @lang('deliveryAgent::app.deliveryAgent.create.title')
        </x-slot>

        <x-slot:content>

            <!-- Personal Information Row 1: First Name & Last Name -->
            <div class="flex gap-4 max-sm:flex-wrap">
                <!-- First Name -->
                <x-admin::form.control-group  class="mb-2.5 w-full">
                    <x-admin::form.control-group.label class="required">
                        @lang('deliveryAgent::app.deliveryAgent.create.first-name')
                    </x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="text"
                        name="first_name"
                        id="first_name"
                        rules="required"
                        :label="trans('deliveryagent::app.deliveryAgent.create.first-name')"
                        :placeholder="trans('deliveryAgent::app.deliveryAgent.create.first-name')"
                    />
                    <x-admin::form.control-group.error control-name="first_name" />
                </x-admin::form.control-group>

                <!-- Last Name -->
                <x-admin::form.control-group class="mb-2.5 w-full">
                    <x-admin::form.control-group.label class="required">
                        @lang('deliveryAgent::app.deliveryAgent.create.last-name')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="text"
                        id="last_name"
                        name="last_name"
                        rules="required"
                        :label="trans('deliveryAgent::app.deliveryAgent.create.last-name')"
                        :placeholder="trans('deliveryAgent::app.deliveryAgent.create.last-name')"
                    />

                    <x-admin::form.control-group.error control-name="last_name" />
                </x-admin::form.control-group>
            </div>

            <!-- Personal Information Row 2: Gender & Date of Birth -->
            <div class="flex gap-4 max-sm:flex-wrap">
                <!-- Gender -->
                <x-admin::form.control-group class="mb-2.5 w-full">
                    <x-admin::form.control-group.label class="required">
                        @lang('deliveryAgent::app.deliveryAgent.create.gender')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="select"
                        id="gender"
                        name="gender"
                        rules="required"
                        :label="trans('deliveryAgent::app.deliveryAgent.create.gender')"
                    >
                        <option value="">
                            @lang('deliveryAgent::app.deliveryAgent.create.select-gender')
                        </option>

                        <option value="Male">
                            @lang('deliveryAgent::app.deliveryAgent.create.male')
                        </option>

                        <option value="Female">
                            @lang('deliveryAgent::app.deliveryAgent.create.female')
                        </option>

                        <option value="Other">
                            @lang('deliveryAgent::app.deliveryAgent.create.other')
                        </option>
                    </x-admin::form.control-group.control>
                    <x-admin::form.control-group.error control-name="gender" />
                </x-admin::form.control-group>

                <!-- Date of Birth -->
                <x-admin::form.control-group class="mb-2.5 w-full">
                    <x-admin::form.control-group.label>
                        @lang('deliveryAgent::app.deliveryAgent.create.date-of-birth')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="date"
                        id="dob"
                        name="date_of_birth"
                        :label="trans('deliveryAgent::app.deliveryAgent.create.date-of-birth')"
                        :placeholder="trans('deliveryAgent::app.deliveryAgent.create.date-of-birth')"
                    />

                    <x-admin::form.control-group.error control-name="date_of_birth" />
                </x-admin::form.control-group>
            </div>

            <!-- Contact Information Row: Email & Phone -->
            <div class="flex gap-4 max-sm:flex-wrap">
                <!-- Email -->
                <x-admin::form.control-group class="mb-2.5 w-full">
                    <x-admin::form.control-group.label class="required">
                        @lang('deliveryAgent::app.deliveryAgent.create.email')
                    </x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="email"
                        name="email"
                        id="email"
                        rules="required|email"
                        :label="trans('deliveryAgent::app.deliveryAgent.create.email')"
                        placeholder="email@example.com"
                    />
                    <x-admin::form.control-group.error control-name="email" />
                </x-admin::form.control-group>

                <!-- Phone -->
                <x-admin::form.control-group class="mb-2.5 w-full">
                    <x-admin::form.control-group.label class="required">
                        @lang('deliveryAgent::app.deliveryAgent.create.phone')
                    </x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="text"
                        name="phone"
                        id="phone"
                        rules="required|phone"
                        :label="trans('deliveryAgent::app.deliveryAgent.create.phone')"
                        :placeholder="trans('deliveryAgent::app.deliveryAgent.create.phone')"
                    />
                    <x-admin::form.control-group.error control-name="phone" />
                </x-admin::form.control-group>
            </div>

            <!-- Password Section Row 1: Password & Confirm Password -->
            <div class="flex gap-4 max-sm:flex-wrap">
                <!-- Password -->
                <x-admin::form.control-group class="mb-2.5 w-full">
                    <x-admin::form.control-group.label class="required">
                        @lang('deliveryAgent::app.deliveryAgent.create.password')
                    </x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="password"
                        name="password"
                        id="password"
                        rules="required|min:6"
                        :label="trans('deliveryAgent::app.deliveryAgent.create.password')"
                        :placeholder="trans('deliveryAgent::app.deliveryAgent.create.password')"
                    />
                    <x-admin::form.control-group.error control-name="password" />
                </x-admin::form.control-group>

                <!-- Confirm Password -->
                <x-admin::form.control-group class="mb-2.5 w-full">
                    <x-admin::form.control-group.label class="required">
                        @lang('deliveryAgent::app.deliveryAgent.create.confirm-password')
                    </x-admin::form.control-group.label>

                    <x-admin::form.control-group.control
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        rules="confirmed:@password"
                        :label="trans('deliveryAgent::app.deliveryAgent.create.confirm-password')"
                        :placeholder="trans('deliveryAgent::app.deliveryAgent.create.confirm-password')"
                    />
                    <x-admin::form.control-group.error control-name="password_confirmation" />
                </x-admin::form.control-group>
            </div>


    </x-slot>

    <x-slot:footer>
        <x-admin::button
        button-type="submit"
        class="primary-button"
        :title="trans('deliveryAgent::app.deliveryAgent.create.create-btn')"
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

                    this.$axios.post("{{ route('admin.deliveryAgents.store') }}", params)
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
