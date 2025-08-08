<v-create-delivery-range-form
    :deliveryAgent="deliveryagent"
    ref="ShowDeliveryRangeCreateComponent"
    @range-created="rangeCreated"
>
    <div class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"></div>
</v-create-delivery-range-form>

@if (bouncer()->hasPermission('delivery.deliveryAgent.range.create'))
    <div
        class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"
        @click="$refs.ShowDeliveryRangeCreateComponent.openModal()"
    >
        @lang('deliveryagent::app.range.create.index-create-btn')
    </div>
@endif

@pushOnce('scripts')


    <script
        type="text/x-template"
        id="v-range-form-template"
    >
        <div id="range-form">
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit, reset, setErrors }"
                as="div"

            >
                <form @submit="handleSubmit($event, create)" >
                    <x-admin::modal ref="modal" ref="RangeCreateModal">


                        <x-slot:header>
                            @lang('deliveryagent::app.range.create.title')
                            </x-slot>

                            <x-slot:content>
                                <x-admin::form.control-group class="w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('deliveryagent::app.range.create.country')
                                    </x-admin::form.control-group.label>
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="country"
                                        rules="required"
                                        v-model="country"
                                        :label="trans('deliveryagent::app.range.create.country')"
                                    >
                                        <option value="" disabled selected hidden>
                                            @lang('deliveryagent::app.range.create.select_country')
                                        </option>

                                        @foreach (core()->countries() as $country)
                                            <option value="{{ $country->code }}">{{ $country->name }}
                                            </option>
                                        @endforeach
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error control-name="country" />
                                </x-admin::form.control-group>

                                <!-- State Name -->
                                <x-admin::form.control-group class="w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('deliveryagent::app.range.create.state')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        id="state"
                                        name="state"
                                        rules="required"
                                        v-model="state"
                                        :label="trans('deliveryagent::app.range.create.state')"
                                        :placeholder="trans('deliveryagent::app.range.create.state')"
                                        ::disabled="!haveStates()"
                                    >
                                        <option value="" disabled selected hidden>
                                            @lang('deliveryagent::app.range.create.select_state')
                                        </option>
                                        <option
                                            v-for='(state, index) in countryStates[country]'
                                            :value="state.code"
                                        >
                                            @{{ state.default_name }}
                                        </option>
                                    </x-admin::form.control-group.control>
                                    <x-admin::form.control-group.error control-name="state" />

                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-2.5 w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('deliveryagent::app.range.create.area-name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="area_name"
                                        name="area_name"
                                        rules="required"
                                        :label="trans('deliveryagent::app.range.create.area-name')"
                                        :placeholder="trans('deliveryagent::app.range.create.area-name')"
                                    />
                                    <x-admin::form.control-group.error control-name="area_name" />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="delivery_agent_id"
                                    v-model="deliveryAgent.id"
                                />

                                <template v-if="country && !haveStates()">
                                    <div class="mt-4 p-3 rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 text-sm">
                                        @lang('deliveryagent::app.range.create.no_states_for_country')
                                    </div>
                                    <div class="mt-3">
                                        <button
                                            class="acma-icon-plus3 text-blue-600 transition-all text-sm"
                                            type="button"
                                            @click="goToCountryView"
                                        >
                                            <span class="text-blue-600">
                                                @lang('deliveryagent::app.range.create.add_state')
                                            </span>

                                        </button>
                                    </div>
                                </template>
                                </x-slot>

                                <x-slot:footer>
                                    <x-admin::button
                                        button-type="submit"
                                        class="primary-button"
                                        :title="trans('deliveryagent::app.range.create.create-btn')"
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
        app.component('v-create-delivery-range-form', {
            template: '#v-range-form-template',
            props: ['deliveryAgent'],
            emits: ['range-created'],


            data() {
                return {
                    country: "",
                    state: "",
                    countryStates: window.countryStates || {},
                    allCountries: @json(core()->countries()),
                    isLoading: false,

                };
            },

            methods: {
                allCountries: undefined,
                openModal() {
                    this.$refs.RangeCreateModal.open();
                },


                create(params, { resetForm, setErrors }) {
                    this.isLoading = true;

                    this.$axios.post("{{ route('admin.range.store') }}", params)
                        .then((response) => {
                            this.$refs.RangeCreateModal.close();
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            this.$emit('range-created', response.data.data);
                            resetForm();
                            this.isLoading = false;

                        })

                        .catch((error) => {
                            this.isLoading = false;

                            if (error.response?.status === 422) {
                                setErrors(error.response.data.errors);

                            }
                        });
                },
                haveStates() {
                    /*
                    * The double negation operator is used to convert the value to a boolean.
                    * It ensures that the final result is a boolean value,
                    * true if the array has a length greater than 0, and otherwise false.
                    */
                    return !!this.countryStates[this.country]?.length;
                },

                goToCountryView() {
                    const countryObj = this.allCountries.find(c => c.code === this.country);

                    if (!countryObj || !countryObj.id) return;

                    const url = `{{ route('admin.country.edit', ':id') }}`.replace(':id', countryObj.id);
                    window.location.href = url;
                }


            },

        });

    </script>
@endPushOnce
