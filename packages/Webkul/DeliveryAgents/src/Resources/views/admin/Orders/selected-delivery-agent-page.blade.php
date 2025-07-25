<v-create-delivery-range-form
    ref="ShowDeliveryRangeCreateComponent"
    @range-created="rangeCreated"
>
    <div class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"></div>
</v-create-delivery-range-form>

@if (bouncer()->hasPermission('delivery.deliveryAgent.edit'))
    <div
        class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
        @click="$refs.ShowDeliveryRangeCreateComponent.openModal()"
    >
        <span class="acma-icon-how_to_reg text-2xl"></span>

        @lang('deliveryagent::app.order.index.select-delivery-agent-btn')
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
                    <x-admin::drawer
                        position="right"
                        width="50%"

                        ref="modal" ref="RangeCreateModal">





                            <x-slot:header >  <!-- Pass your custom css to customize header -->
                                Drawer Header
                                </x-slot>

                                <x-slot:content class="!p-5"> <!-- Pass your custom css to customize header -->
                                    Drawer Content
                                    </x-slot>



                    </x-admin::drawer>
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

                    const url = `{{ route('admin.country.view', ':id') }}`.replace(':id', countryObj.id);
                    window.location.href = url;
                }


            },

        });

    </script>
@endPushOnce
