
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
        @if (bouncer()->hasPermission('delivery.deliveryAgent.edit'))
            <div
                class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"

                @click="$refs.RangeEditModal.toggle()"
            >
                @lang('deliveryagent::app.range.edit.edit-btn')
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
                                        v-model="editingRange.country"
                                        :label="trans('deliveryagent::app.range.create.country')"
                                    >
                                        <option value="" disabled selected hidden>
                                            @lang('deliveryagent::app.range.create.select_country')
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
                                        @lang('deliveryagent::app.range.create.state')
                                    </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            id="state"
                                            name="state"
                                            rules="required"
                                            :label="trans('admin::app.customers.customers.view.address.edit.state')"
                                            :placeholder="trans('admin::app.customers.customers.view.address.edit.state')"
                                            v-model="editingRange.state"
                                            ::disabled="!haveStates()"


                                        >
                                            <option
                                                v-for='(state, index) in countryStates[editingRange.country]'
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
                                        v-model="editingRange.area_name"
                                        :label="trans('deliveryagent::app.range.create.area-name')"
                                        :placeholder="trans('deliveryagent::app.range.create.area-name')"
                                    />
                                    <x-admin::form.control-group.error control-name="area_name" />
                                </x-admin::form.control-group>


                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="delivery_agent_id"
                                    v-model="editingRange.id"
                                />

                                <template v-if="range.country && !haveStates()">
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
        app.component('delivery-range-edit-component', {
            template: '#v-range-edit-form-template',
            props: ['range'],
            emits: ['range-updated'],
            data() {
                return {
                    country: "",
                    allCountries: @json(core()->countries()),
                    countryStates: window.countryStates || {},
                    editingRange: null,
                    isLoading: false,
                };
            },
            mounted() {
                this.resetEditingRange();
            },
            openModal() {
                this.resetEditingRange();
                this.$refs.RangeEditModal.toggle();
            },


            methods: {
                resetEditingRange() {
                    // إنشاء نسخة مؤقتة قابلة للتعديل بدون التأثير على الأصل
                    this.editingRange = JSON.parse(JSON.stringify(this.range));
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
                    return !!this.countryStates[this.editingRange.country]?.length;
                },

                goToCountryView() {
                    const countryObj = this.allCountries.find(c => c.code === this.editingRange.country);

                    if (!countryObj || !countryObj.id) return;

                    const url = `{{ route('admin.country.view', ':id') }}`.replace(':id', countryObj.id);
                    window.location.href = url;
                }


            },


        });

    </script>
@endPushOnce
