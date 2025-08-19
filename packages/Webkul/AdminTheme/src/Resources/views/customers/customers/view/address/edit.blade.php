<v-edit-customer-address
    :address="address"
    @address-updated="addressUpdated"
></v-edit-customer-address>

@pushOnce('scripts')
    <!-- Customer Address Form -->
    <script
        type="text/x-template"
        id="v-edit-customer-address-template"
    >
        <div>
            <!-- Address Edit Button -->
            @if (bouncer()->hasPermission('customers.addresses.edit'))
                <p
                    class="cursor-pointer text-blue-600 transition-all hover:underline"
                    @click="$refs.customerAddressModal.toggle()"
                >
                    @lang('admin::app.customers.customers.view.address.edit.edit-btn')
                </p>
            @endif

            {!! view_render_event('bagisto.admin.customers.addresses.edit.before') !!}

            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                {!! view_render_event('bagisto.admin.customers.addresses.edit.edit_form_controls.before') !!}

                <form
                    @submit="handleSubmit($event, update)"
                    ref="createOrUpdateForm"
                >
                    <!-- Address Edit Drawer -->
                    <x-admin::drawer
                        width="350px"
                        ref="customerAddressModal"
                    >
                        <!-- Modal Header -->
                        <x-slot:header class="py-5">
                            <p class="text-lg font-bold text-gray-800 dark:text-white">
                                @lang('admin::app.customers.customers.view.address.edit.title')
                            </p>
                        </x-slot>

                        <!-- Drawer Content -->
                        <x-slot:content>

                            {!! view_render_event('bagisto.admin.customer.addresses.edit.before') !!}

                            <!-- Company Name -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.customers.customers.view.address.edit.company-name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="customer_id"
                                    ::value="address.customer_id"
                                />

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="address_id"
                                    ::value="address.id"
                                />

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="company_name"
                                    ::value="address.company_name"
                                    :label="trans('admin::app.customers.customers.view.address.edit.company-name')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.edit.company-name')"
                                />

                                <x-admin::form.control-group.error control-name="company_name" />
                            </x-admin::form.control-group>

                            <!-- Vat Id -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.customers.customers.view.address.edit.vat-id')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="vat_id"
                                    ::value="address.vat_id"
                                    :label="trans('admin::app.customers.customers.view.address.edit.vat-id')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.edit.vat-id')"
                                />

                                <x-admin::form.control-group.error control-name="vat_id" />
                            </x-admin::form.control-group>

                            <!-- First Name -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.view.address.edit.first-name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="first_name"
                                    ::value="address.first_name"
                                    rules="required"
                                    :label="trans('admin::app.customers.customers.view.address.edit.first-name')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.edit.first-name')"
                                />

                                <x-admin::form.control-group.error control-name="first_name" />
                            </x-admin::form.control-group>

                            <!-- Last Name -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.view.address.edit.last-name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="last_name"
                                    ::value="address.last_name"
                                    rules="required"
                                    :label="trans('admin::app.customers.customers.view.address.edit.last-name')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.edit.last-name')"
                                />

                                <x-admin::form.control-group.error control-name="last_name" />
                            </x-admin::form.control-group>

                            <!-- E-mail -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.view.address.edit.email')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="email"
                                    ::value="address.email"
                                    rules="required|email"
                                    :label="trans('admin::app.customers.customers.view.address.edit.email')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.edit.email')"
                                />

                                <x-admin::form.control-group.error control-name="email" />
                            </x-admin::form.control-group>

                            <!--Phone number -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.view.address.edit.phone')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="phone"
                                    ::value="address.phone"
                                    rules="required|phone"
                                    :label="trans('admin::app.customers.customers.view.address.edit.phone')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.edit.phone')"
                                />

                                <x-admin::form.control-group.error control-name="phone" />
                            </x-admin::form.control-group>

                            <!-- Country Name -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.customers.customers.view.address.edit.country')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="country"
                                    rules="required"
                                    :label="trans('admin::app.customers.customers.view.address.edit.country')"
                                    v-model="address.country"
                                >
                                    <option value="" >
                                        @lang('admin::app.customers.customers.view.address.create.select-country')
                                    </option>

                                    @foreach (core()->countries() as $country)
                                        <option
                                            {{ $country->code === config('app.default_country') ? 'selected' : '' }}
                                            value="{{ $country->code }}"
                                        >
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="country" />
                            </x-admin::form.control-group>

                            <!-- State Name -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.view.address.edit.state')
                                </x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="select"
                                        id="state"
                                        name="state"
                                        rules="required"
                                        :label="trans('admin::app.customers.customers.view.address.edit.state')"
                                        :placeholder="trans('admin::app.customers.customers.view.address.edit.state')"
                                        v-model="state"
                                        ::disabled="!haveStates()"

                                    >
                                        <option value="" >
                                            @lang('admin::app.customers.customers.view.address.create.select_state')
                                        </option>
                                        <option
                                            v-for='(state, index) in countryStates[address.country]'
                                            :value="state.code"
                                        >
                                            @{{ state.default_name }}
                                        </option>
                                    </x-admin::form.control-group.control>
                                <x-admin::form.control-group.error control-name="state" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.view.address.create.city')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    id="state_area_id"
                                    name="state_area_id"
                                    rules="required"
                                    :label="trans('deliveryagent::app.range.create.area-name')"
                                    :placeholder="trans('deliveryagent::app.range.create.area-name')"
                                    v-model="area"
                                    ::disabled="!haveAreas()"
                                >
                                    <option value="">
                                        @lang('admin::app.customers.customers.view.address.create.select_state_area')
                                    </option>
                                    <option
                                        v-for="(opt,index) in stateAreas[state]"
                                        :key="opt.id"
                                        :value="String(opt.id)"
                                    >
                                        @{{ opt.area_name }}
                                    </option>
                                </x-admin::form.control-group.control>

                                {{-- حقل المدينة دائماً موجود: يترسل للنموذج ويصير read-only عندما عندك مناطق --}}
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="city"
                                    v-model="city"
                                    rules="required"
                                    ::readonly="haveAreas()"
                                    :label="trans('admin::app.customers.customers.view.address.create.city')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.create.city')"
                                    class="mt-3"
                                />
                                <x-admin::form.control-group.error control-name="city" />
                            </x-admin::form.control-group>


                            <!-- Street Address -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.view.address.edit.street-address')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="address[0]"
                                    name="address[0]"
                                    ::value="address.address.split('\n')[0]"
                                    rules="required"
                                    :label="trans('admin::app.customers.customers.view.address.edit.street-address')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.edit.street-address')"
                                />

                                <x-admin::form.control-group.error
                                    class="mb-2"
                                    control-name="address[0]"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                @if (core()->getConfigData('customer.address.information.street_lines') > 1)
                                    @for ($i = 1; $i < core()->getConfigData('customer.address.information.street_lines'); $i++)
                                        <x-admin::form.control-group.control
                                            type="text"
                                            id="address[{{ $i }}]"
                                            name="address[{{ $i }}]"
                                            ::value="address.address.split('\n')[{{ $i }}]"
                                            :label="trans('admin::app.customers.customers.view.address.edit.street-address')"
                                            :placeholder="trans('admin::app.customers.customers.view.address.edit.street-address')"
                                        />

                                        <x-admin::form.control-group.error control-name="address[{{ $i }}]" />
                                    @endfor
                                @endif
                            </x-admin::form.control-group>

                            <!-- PostCode -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.view.address.edit.post-code')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="postcode"
                                    ::value="address.postcode"
                                    rules="required|postcode"
                                    :label="trans('admin::app.customers.customers.view.address.edit.post-code')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.edit.post-code')"
                                />

                                <x-admin::form.control-group.error control-name="postcode" />
                            </x-admin::form.control-group>

                            <!-- Default Address -->
                            <x-admin::form.control-group class="flex items-center gap-2.5">
                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    id="default_address"
                                    name="default_address"
                                    :value="1"
                                    for="default_address"
                                    :label="trans('admin::app.customers.customers.view.address.edit.default-address')"
                                    ::checked="address.default_address"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="default_address"
                                >
                                    @lang('admin::app.customers.customers.view.address.edit.default-address')
                                </label>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group.error control-name="default_address" />

                            {!! view_render_event('bagisto.admin.customers.edit.after') !!}

                            <!-- Modal Submission -->
                            <x-admin::button
                                button-type="submit"
                                class="primary-button w-full max-w-full justify-center"
                                :title="trans('admin::app.customers.customers.view.address.edit.save-btn-title')"
                                ::loading="isLoading"
                                ::disabled="isLoading"
                            />
                        </x-slot>
                    </x-admin::drawer>
                </form>

                {!! view_render_event('bagisto.admin.customers.addresses.edit.edit_form_controls.after') !!}

            </x-admin::form>

            {!! view_render_event('bagisto.admin.customers.addresses.edit.after') !!}
        </div>
    </script>

    <script type="module">
        app.component('v-edit-customer-address', {
            template: '#v-edit-customer-address-template',

            props: ['address'],

            emits: ['address-updated'],

            data() {
                return {
                    state:this.address.state,
                    city: this.address.city,
                    area: this.address.state_area_id,
                    countryStates: @json(core()->groupedStatesByCountries()),
                    stateAreas: @json(myHelper()->groupedAreasByStatesCode()),
                    isLoading: false,
                };
            },

            methods: {
                update(params, { resetForm, setErrors }) {
                    this.isLoading = true;

                    let formData = new FormData(this.$refs.createOrUpdateForm);

                    formData.append('_method', 'put');

                    formData.append('default_address', formData.get('default_address') ? 1 : 0);

                    this.$axios.post(`{{ route('admin.customers.customers.addresses.update', '') }}/${params?.address_id}`, formData)
                        .then((response) => {
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.$emit('address-updated', response.data.data);

                            this.isLoading = false;

                            this.$refs.customerAddressModal.toggle();
                        })
                        .catch(error => {
                            this.isLoading = false;

                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },

                haveStates() {
                    const list = this.countryStates?.[this.address.country]
                    return Array.isArray(list) && list.length > 0;

                },
                haveAreas() {
                    const list = this.stateAreas?.[this.state];
                    return Array.isArray(list) && list.length > 0;
                },
            },
            watch: {
                // عند تغيير الولاية: صفّر المنطقة والمدينة
                'address.country'() {
                    this.state = '';
                },
                'state'() {
                    this.area = '';
                },

                // عند اختيار المنطقة: عيّن اسم المدينة = اسم المنطقة
                area(newAreaId) {
                    if (!this.haveAreas() || !newAreaId) {
                        this.city = '';
                        return;
                    }

                    const list = this.stateAreas[this.state] || [];

                    // قيم select عادة تكون string → نحول للرقم للمطابقة
                    const id = Number(newAreaId);
                    const selected = list.find(a => Number(a.id) === id);

                    this.city = selected ? selected.area_name : '';
                },
            },
        });
    </script>
@endPushOnce
