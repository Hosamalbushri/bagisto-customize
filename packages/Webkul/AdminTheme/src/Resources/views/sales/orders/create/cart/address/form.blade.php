@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checkout-address-form-template"
    >
        <div class="mt-2">
            <x-admin::form.control-group class="hidden">
                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.id'"
                    ::value="address.id"
                />
            </x-admin::form.control-group>

            <!-- Company Name -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.sales.orders.create.cart.address.company-name')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.company_name'"
                    ::value="address.company_name"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.company-name')"
                />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.company_name.after') !!}

            <!-- VatId Name -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.sales.orders.create.cart.address.vat-id')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.vat_id'"
                    ::value="address.vat_id"
                    :label="trans('admin::app.sales.orders.create.cart.address.vat-id')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.vat-id')"
                />

                <x-admin::form.control-group.error ::name="controlName + '.vat_id'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.vat_id.after') !!}

            <!-- First Name -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required !mt-0">
                    @lang('admin::app.sales.orders.create.cart.address.first-name')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.first_name'"
                    ::value="address.first_name"
                    rules="required"
                    :label="trans('admin::app.sales.orders.create.cart.address.first-name')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.first-name')"
                />

                <x-admin::form.control-group.error ::name="controlName + '.first_name'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.first_name.after') !!}

            <!-- Last Name -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required !mt-0">
                    @lang('admin::app.sales.orders.create.cart.address.last-name')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.last_name'"
                    ::value="address.last_name"
                    rules="required"
                    :label="trans('admin::app.sales.orders.create.cart.address.last-name')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.last-name')"
                />

                <x-admin::form.control-group.error ::name="controlName + '.last_name'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.last_name.after') !!}

            <!-- Email -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required !mt-0">
                    @lang('admin::app.sales.orders.create.cart.address.email')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="email"
                    ::name="controlName + '.email'"
                    ::value="address.email"
                    rules="required|email"
                    :label="trans('admin::app.sales.orders.create.cart.address.email')"
                    placeholder="email@example.com"
                />

                <x-admin::form.control-group.error ::name="controlName + '.email'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.email.after') !!}

            <!-- Phone Number -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required !mt-0">
                    @lang('admin::app.sales.orders.create.cart.address.telephone')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.phone'"
                    ::value="address.phone"
                    rules="required|numeric|phone"
                    :label="trans('admin::app.sales.orders.create.cart.address.telephone')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.telephone')"
                />

                <x-admin::form.control-group.error ::name="controlName + '.phone'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.phone.after') !!}
            <!-- Country -->
            <x-admin::form.control-group class="!mb-4">
                <x-admin::form.control-group.label class="required">
                    @lang('admin::app.sales.orders.create.cart.address.country')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="select"
                    ::name="controlName + '.country'"
                    ::value="address.country"
                    v-model="selectedCountry"
                    rules="required"
                    :label="trans('admin::app.sales.orders.create.cart.address.country')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.country')"
                >
                    <option value="">
                        @lang('admin::app.sales.orders.create.cart.address.select-country')
                    </option>

                    <option
                        v-for="country in countries"
                        :value="country.code"
                    >
                        @{{ country.name }}
                    </option>
                </x-admin::form.control-group.control>

                <x-admin::form.control-group.error ::name="controlName + '.country'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.country.after') !!}

            <!-- CountryState -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    @lang('admin::app.sales.orders.create.cart.address.state')
                </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            ::name="controlName + '.state'"
                            ::value="address.state"
                            v-model="selectedState"
                            rules="required"
                            :label="trans('admin::app.sales.orders.create.cart.address.state')"
                            :placeholder="trans('admin::app.sales.orders.create.cart.address.state')"
                            ::disabled="!haveStates"

                        >
                            <option value="">
                                @lang('admin::app.sales.orders.create.cart.address.select-state')
                            </option>

                            <option
                                v-for='state in states[selectedCountry]'
                                :value="state.code"
                            >
                                @{{ state.default_name }}
                            </option>
                        </x-admin::form.control-group.control>
                <x-admin::form.control-group.error ::name="controlName + '.state'" />
            </x-admin::form.control-group>


            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.state.after') !!}

            <!-- City -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required !mt-0">
                    @lang('admin::app.sales.orders.create.cart.address.city')
                </x-admin::form.control-group.label>
                <x-admin::form.control-group.control
                    type="select"
                    ::name="controlName + '.state_area_id'"
                    ::value="address.state_area_id"
                    v-model="selectedArea"
                    rules="required"
                    :label="trans('admin::app.sales.orders.create.cart.address.city')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.city')"
                    ::disabled="!haveAreas"
                >
                    <option value="">
                        @lang('admin::app.customers.customers.view.address.create.select_state_area')
                    </option>

                    <option
                        v-for="opt in areas[selectedState]"
                        :key="opt.id"
                        :value="String(opt.id)"
                    >
                        @{{ opt.area_name }}
                    </option>
                </x-admin::form.control-group.control>

                <x-admin::form.control-group.control
                    type="hidden"
                    ::name="controlName + '.city'"
                    ::value="address.city"
                    v-model="city"
                    rules="required"
                    :label="trans('admin::app.sales.orders.create.cart.address.city')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.city')"
                />


                <x-admin::form.control-group.error ::name="controlName + '.city'" />
            </x-admin::form.control-group>


            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.city.after') !!}

            <!-- Street Address -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required !mt-0">
                    @lang('admin::app.sales.orders.create.cart.address.street-address')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.address.[0]'"
                    ::value="address.address[0]"
                    rules="required|address"
                    :label="trans('admin::app.sales.orders.create.cart.address.street-address')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.street-address')"
                />

                <x-admin::form.control-group.error
                    class="mb-2"
                    ::name="controlName + '.address.[0]'"
                />

                @if (core()->getConfigData('customer.address.information.street_lines') > 1)
                    @for ($i = 1; $i < core()->getConfigData('customer.address.information.street_lines'); $i++)
                        <x-admin::form.control-group.control
                            type="text"
                            ::name="controlName + '.address.[{{ $i }}]'"
                            class="mt-2"
                            rules="address"
                            :label="trans('admin::app.sales.orders.create.cart.address.street-address')"
                            :placeholder="trans('admin::app.sales.orders.create.cart.address.street-address')"
                        />

                        <x-admin::form.control-group.error
                            class="mb-2"
                            ::name="controlName + '.address.[{{ $i }}]'"
                        />
                    @endfor
                @endif
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.address.after') !!}

            <!-- Postcode -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="{{ core()->isPostCodeRequired() ? 'required' : '' }} !mt-0">
                    @lang('admin::app.sales.orders.create.cart.address.postcode')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.postcode'"
                    ::value="address.postcode"
                    rules="{{ core()->isPostCodeRequired() ? 'required' : '' }}|postcode"
                    :label="trans('admin::app.sales.orders.create.cart.address.postcode')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.postcode')"
                />

                <x-admin::form.control-group.error ::name="controlName + '.postcode'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.postcode.after') !!}

        </div>
    </script>

    <script type="module">
        app.component('v-checkout-address-form', {
            template: '#v-checkout-address-form-template',

            props: {
                controlName: {
                    type: String,
                    required: true,
                },

                address: {
                    type: Object,

                    default: () => ({
                        id: 0,
                        company_name: '',
                        first_name: '',
                        last_name: '',
                        email: '',
                        address: [],
                        country: '',
                        state: '',
                        state_area_id:'',
                        city: '',
                        postcode: '',
                        phone: '',
                    }),
                },
            },

            data() {
                return {
                    selectedCountry: this.address.country || @json(core()->getConfigData('general.location.store.default_country')),
                    selectedState: this.address.state,
                    selectedArea: this.address.state_area_id,

                    countries: [],

                    states: null,

                    areas:  null,

                }
            },

            created() {
                this.getCountries();
                this.getStates();
                this.getAreas();
            },

            computed: {
                haveStates() {
                    return !! this.states[this.selectedCountry]?.length;
                },
                haveAreas() {
                    const list = this.areas?.[this.selectedState];
                    return Array.isArray(list) && list.length > 0;
                },
            },
            watch: {
                // عند تغيير الولاية: صفّر المنطقة والمدينة
                'selectedCountry'() {
                    this.selectedState = '';
                },
                'selectedState'() {
                    this.selectedArea = '';
                },

                // عند اختيار المنطقة: عيّن اسم المدينة = اسم المنطقة
                selectedArea(newAreaId) {
                    if (!this.haveAreas || !newAreaId) {
                        this.city = '';
                        return;
                    }

                    const list = this.areas[this.selectedState] || [];

                    // قيم select عادة تكون string → نحول للرقم للمطابقة
                    const id = Number(newAreaId);
                    const selected = list.find(a => Number(a.id) === id);

                    this.city = selected ? selected.area_name : '';
                },
            },

            methods: {
                getCountries() {
                    this.$axios.get("{{ route('shop.api.core.countries') }}")
                        .then(response => {
                            this.countries = response.data.data;
                        })
                        .catch(() => {});
                },

                getStates() {
                    this.$axios.get("{{ route('shop.api.core.states') }}")
                        .then(response => {
                            this.states = response.data.data;
                        })
                        .catch(() => {});
                },
                getAreas() {
                    this.$axios.get("{{ route('shop.api.core.areas') }}")
                        .then(response => {
                            this.areas = response.data.data;
                        })
                        .catch(() => {});
                },
            }
        });
    </script>
@endPushOnce
