<x-admin::layouts>
    <v-country-view>

    </v-country-view>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-country-template"
        >
            <x-slot:title>
                @php
                    $label = trans('deliveryAgent::app.country.view.title');
                @endphp
                {{ isset($country) && !empty($country->name) ? ($label . ': ' . $country->name) : $label }}
            </x-slot:title>
                <div class="grid">
                    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
                        <div class="flex items-center gap-2.5">
                            <template
                                v-if="! country"
                                class="flex gap-5"
                            >
                                <p class="shimmer w-32 p-2.5"></p>

                                <p class="shimmer w-14 p-2.5"></p>
                            </template>

                            <template v-else>
                                <h1
                                    v-if="country"
                                    class="text-xl font-bold leading-6 text-gray-800 dark:text-white"
                                    @lang('deliveryAgent::deliveryagent::app.country.view.title')
                                    v-text="`@lang('deliveryAgent::app.country.view.title'):  ${country.name}`"></h1>
                            </template>
                        </div>

                        <!-- Back Button -->
                        <a
                            href="{{ route('admin.country.index') }}"
                            class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                        >
                            @lang('deliveryAgent::app.country.view.back-btn')
                        </a>
                    </div>
                </div>


                <!-- Content -->
                <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
                    <!-- Left Component -->

                    <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                        @if (bouncer()->hasPermission('delivery.countries.states'))
                            @include('DeliveryAgents::admin.Countries.view.States.index')

                        @endif
                    </div>
                    <!-- Right Component -->
                    <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                        <template v-if="! country">
                            <x-admin::shimmer.accordion class="h-[271px] w-[360px]"/>
                        </template>

                        <template v-else>
                            <x-admin::accordion>
                                <x-slot:header>
                                    <div class="flex w-full">
                                        <p class="w-full p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                            @lang('deliveryAgent::app.country.view.country')
                                        </p>

                                        <!--Countries Edit Component -->
                                        @include('DeliveryAgents::admin.Countries.view.edit')
                                    </div>
                                </x-slot:header>

                                <x-slot:content>
                                    <template v-if="country">
                                        <div class="grid gap-y-2.5">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('deliveryAgent::app.country.view.name')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="name"
                                                    v-model="country.name"
                                                    readonly
                                                    disabled
                                                />
                                            </x-admin::form.control-group>

                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('deliveryAgent::app.country.view.code')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="code"
                                                    v-model="country.code"
                                                    readonly
                                                    disabled
                                                />
                                            </x-admin::form.control-group>
                                        </div>
                                    </template>

                                </x-slot:content>
                            </x-admin::accordion>
                        </template>
                    </div>
                </div>


        </script>
        <script type="module">
            app.component('v-country-view', {
                template: '#v-country-template',
                data() {
                    return {
                        country: @json($country),
                        statesCount: {{ is_countable($country->states) ? count($country->states) : 0}},
                        isUpdating: {},
                    };
                },
                methods: {
                    updateCountry(data) {
                        this.country = {
                            ...this.country,
                            ...data.country,
                        };
                    },
                    onStateCreated(state) {
                        this.$refs.StatesDatagrid.get();

                    }

                }


            })
        </script>

    @endpushonce

</x-admin::layouts>
