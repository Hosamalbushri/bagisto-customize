
<delivery-range-edit-component
    :range="range"
    @range-updated="rangeUpdated"
>

</delivery-range-edit-component>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-range-edit-form-template"
    >
        @if (bouncer()->hasPermission('delivery.deliveryAgent.range.edit'))
            <div
                class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"

                @click="$refs.RangeEditModal.toggle()"
            >
                @lang('deliveryagent::app.range.edit.view-edit-btn')
            </div>

        @endif
        <div id="range-form">
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit, reset, setErrors }"
                as="div"
            >
                <form @submit="handleSubmit($event,update)" >
                    <x-admin::modal  ref="RangeEditModal">
                        <x-slot:header>
                            @lang('deliveryagent::app.range.edit.title')
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
                                        v-model="countryId"
                                        :label="trans('deliveryagent::app.range.edit.country')"
                                    >
                                        <option value="" disabled selected hidden>
                                            @lang('deliveryagent::app.range.edit.select_country')
                                        </option>

                                        @foreach (core()->countries() as $country)
                                            <option
                                                {{ $country->id === config('app.default_country') ? 'selected' : '' }}
                                                value="{{ $country->id }}"
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
                                        @lang('deliveryagent::app.range.edit.state')
                                    </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            id="state"
                                            name="state"
                                            rules="required"
                                            :label="trans('deliveryagent::app.range.edit.state')"
                                            :placeholder="trans('deliveryagent::app.range.edit.state')"
                                            v-model="editingRange.state_area.country_state_id"
                                            ::disabled="!haveStates()"
                                        >
                                            <option
                                                v-for='(state, index) in countryStates[countryId]'
                                                :value="state.id"
                                            >
                                                @{{ state.default_name }}
                                            </option>
                                        </x-admin::form.control-group.control>


                                    <x-admin::form.control-group.error control-name="state" />

                                </x-admin::form.control-group>
                                <!-- Area -->
                                <x-admin::form.control-group class="w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('deliveryagent::app.range.edit.area-name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        id="state_area_id"
                                        name="state_area_id"
                                        rules="required"
                                        v-model="editingRange.state_area_id"
                                        :label="trans('deliveryagent::app.range.edit.area-name')"
                                        :placeholder="trans('deliveryagent::app.range.edit.area-name')"
                                        ::disabled="!haveAreas()"
                                    >
                                        <option value="" disabled selected hidden>
                                            @lang('deliveryagent::app.range.edit.select_state_area')
                                        </option>

                                        <option
                                            v-for="(area, index) in stateAreas[editingRange.state_area.country_state_id]"
                                            :value="area.id"
                                        >
                                            @{{ area.area_name }}
                                        </option>
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>


                                <template v-if="editingRange.state_area.country_code && !haveStates()">
                                    <div class="mt-4 p-3 rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 text-sm">
                                        @lang('deliveryagent::app.range.edit.no_states_for_country')
                                    </div>
                                    <div class="mt-3">
                                        @if (bouncer()->hasPermission('delivery.countries.country.edit'))

                                        <button
                                            class="acma-icon-plus3 text-blue-600 transition-all text-sm"
                                            type="button"
                                            @click="goToCountryView"
                                        >
                                            <span class="text-blue-600">
                                                @lang('deliveryagent::app.range.edit.add_state')
                                            </span>

                                        </button>
                                        @endif
                                    </div>
                                </template>
                                <template v-if="editingRange.state_area.country_state_id && !haveAreas()">
                                    <div class="mt-4 p-3 rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 text-sm">
                                        @lang('deliveryagent::app.range.edit.no_areas_for_state')
                                    </div>
                                    <div class="mt-3">
                                        @if (bouncer()->hasPermission('delivery.countries.states.areas.edit'))

                                            <button
                                                class="acma-icon-plus3 text-blue-600 transition-all text-sm"
                                                type="button"
                                                @click="goToStateView"
                                            >
                                            <span class="text-blue-600">
                                                @lang('deliveryagent::app.range.edit.add_area')
                                            </span>

                                            </button>
                                        @endif
                                    </div>
                                </template>
                                </x-slot>


                                <x-slot:footer>
                                    <x-admin::button
                                        button-type="submit"
                                        class="primary-button"
                                        :title="trans('deliveryagent::app.range.edit.edit-btn')"
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
        app.component('delivery-range-edit-component', {
            template: '#v-range-edit-form-template',
            props: ['range'],
            emits: ['range-updated'],
            data() {
                return {
                    allCountries: @json(core()->countries()),
                    countryStates: @json(myHelper()->groupedStatesByCountries()),
                    stateAreas: @json(myHelper()->groupedAreasByStates()),
                    editingRange: null,
                    countryId: null,
                    isLoading: false,
                };
            },
            mounted() {
                this.resetEditingRange();
                this.setCountryIdFromCode();
            },
            openModal() {
                this.resetEditingRange();
                this.$refs.RangeEditModal.toggle();
            },



            methods: {
                setCountryIdFromCode() {
                    const country = this.allCountries.find(c => c.code === this.editingRange.state_area.country_code);
                    this.countryId = country ? country.id : null;
                },

                resetEditingRange() {
                    // إنشاء نسخة مؤقتة قابلة للتعديل بدون التأثير على الأصل
                    this.editingRange = JSON.parse(JSON.stringify(this.range));
                },
                getCountryId(code) {
                    return this.country[code] || code;
                },
                update(params, { resetForm, setErrors }) {
                    this.isLoading = true;

                    this.$axios.post(`{{ route('admin.range.update', ':id') }}`.replace(':id', this.editingRange.id),
                        params
                    )
                        .then((response) => {
                            this.$refs.RangeEditModal.close();
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            this.$emit('range-updated', response.data.data);
                            this.isLoading = false;
                            resetForm();


                        })

                        .catch((error) => {
                            this.isLoading = false;
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: error?.response?.data?.message
                            });

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
                    return !!this.countryStates[this.countryId]?.length;
                },
                haveAreas() {
                    return !!this.stateAreas[this.editingRange.state_area.country_state_id]?.length;
                },

                goToCountryView() {
                    const countryObj = this.countryId;
                    if (!countryObj ) return;

                    const url = `{{ route('admin.country.edit', ':id') }}`.replace(':id', countryObj);
                    window.location.href = url;
                },
                goToStateView() {
                    const statesArray = this.countryStates[this.countryId] || [];
                    const stateObj = statesArray.find(s => s.id === this.editingRange.state_area.country_state_id);

                    if (!stateObj || !stateObj.id) return;

                    const url = `{{ route('admin.states.edit', ':id') }}`.replace(':id', stateObj.id);
                    window.location.href = url;
                },


            },


        });

    </script>
@endPushOnce
