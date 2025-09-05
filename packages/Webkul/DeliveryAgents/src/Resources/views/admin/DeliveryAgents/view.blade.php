<x-admin::layouts>

    <v-delivery-agente-view>
    </v-delivery-agente-view>
    @pushOnce('scripts')

        <script>
            window.countries = @json(core()->countries()->pluck('name', 'code'));
            window.countryStates = @json(core()->groupedStatesByCountries());
            window.stateAreas =@json(myHelper()->groupedAreasByStatesCode());

        </script>

        <script
            type="text/x-template"
            id="v-delivery-agent-view-template"
        >
            <x-slot:title>
                @php
                    $label = trans('deliveryAgent::app.deliveryAgent.view.title');
                @endphp
                {{ isset($deliveryAgent) && !empty($deliveryAgent->name) ? ($label . ': ' . $deliveryAgent->name) : $label }}
            </x-slot:title>

                <div class="grid">
                    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
                        <div class="flex items-center gap-2.5">
                            <!-- shimmer loading while data is loading -->
                            <template v-if="!deliveryagent" class="flex gap-5">
                                <p class="shimmer w-32 p-2.5"></p>
                                <p class="shimmer w-14 p-2.5"></p>
                            </template>

                            <!-- when data is loaded -->
                            <template v-else>
                                <!-- name -->
                                <h1
                                    v-if="deliveryagent"
                                    class="text-xl font-bold leading-6 text-gray-800 dark:text-white"
                                    v-text="`@lang('deliveryAgent::app.deliveryAgent.view.title') : ${deliveryagent.first_name} ${deliveryagent.last_name}`"
                                ></h1>

                                <!-- status -->
                                {{--                                <span--}}
                                {{--                                    v-if="deliveryagent.status"--}}
                                {{--                                    class="label-active mx-1.5 text-sm">--}}
                                {{--                                     @lang('deliveryagent::app.deliveryagents.view.active')--}}
                                {{--                                </span>--}}
                                {{--                                <span--}}
                                {{--                                    v-else--}}
                                {{--                                    class="label-canceled mx-1.5 text-sm">--}}
                                {{--                                      @lang('deliveryagent::app.deliveryagents.view.inactive')--}}
                                {{--                                </span>--}}
                                <span
                                    v-if="deliveryagent && deliveryagent.status==1"
                                    class="mx-1.5 text-sm label-active"
                                    v-text="`@lang('deliveryAgent::app.deliveryAgent.view.active')`"
                                >

                                </span>
                                <span
                                    v-else-if="deliveryagent && deliveryagent.status == 0"
                                    class="mx-1.5 text-sm label-canceled"
                                    v-text="`@lang('deliveryAgent::app.deliveryAgent.view.inactive')`"
                                >
                                </span>
                            </template>
                        </div>

                        <!-- back button -->
                        <a
                            href="{{ route('admin.deliveryagents.index') }}"
                            class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800">
                            @lang('deliveryAgent::app.deliveryAgent.view.back-btn')
                        </a>
                    </div>
                </div>
                <!-- Filters -->
                <div class="mt-7 flex flex-wrap items-center gap-x-1 gap-y-2">

                </div>
                <!-- Content -->
                <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
                    <!-- Left Component -->
                    <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                        @include('DeliveryAgents::admin.DeliveryAgents.orders.index')

                    </div>
                    <!-- Right Component -->
                    <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                        <template v-if="! deliveryagent">
                            <x-admin::shimmer.accordion class="h-[271px] w-[360px]"/>
                        </template>
                        <template v-else>
                            <x-admin::accordion>
                                <x-slot:header>
                                    <div class="flex w-full">
                                        <p class="w-full p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                            @lang('deliveryAgent::app.deliveryAgent.view.delivery-agent')
                                        </p>

                                        <!--Delivery Agents Edit Component -->
                                        @include('DeliveryAgents::admin.DeliveryAgents.view.edit')


                                    </div>
                                </x-slot:header>

                                <x-slot:content>
                                    <div class="grid gap-y-2.5">
                                        <p
                                            class="break-all font-semibold text-gray-800 dark:text-white"
                                            v-text="`${deliveryagent.first_name} ${deliveryagent.last_name}`"
                                        >
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @{{ "@lang('deliveryAgent::app.deliveryAgent.view.email')
                                            ".replace(':email', deliveryagent.email ?? 'N/A') }}
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @{{ "@lang('deliveryAgent::app.deliveryAgent.view.phone')
                                            ".replace(':phone', deliveryagent.phone ?? 'N/A') }}
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @{{ "@lang('deliveryAgent::app.deliveryAgent.view.gender')
                                            ".replace(':gender', deliveryagent.gender ?? 'N/A') }}
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @{{ "@lang('deliveryAgent::app.deliveryAgent.view.date-of-birth')
                                            ".replace(':dob', deliveryagent.date_of_birth ?? 'N/A') }}
                                        </p>

                                    </div>
                                </x-slot:content>
                            </x-admin::accordion>
                        </template>

                        <template v-if="! deliveryagent">
                            <x-admin::shimmer.accordion class="h-[271px] w-[360px]"/>
                        </template>


                        <template v-else>
                            <!-- Ranges listing-->
                            <x-admin::accordion>
                                <x-slot:header>
                                    <div class="flex w-full">
                                        <p class="w-full p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                            @{{ "@lang('deliveryAgent::app.range.view.count')
                                            ".replace(':count',deliveryagent.ranges.length) }}
                                        </p>

                                        <!-- Ranges Create component -->
                                        @if (bouncer()->hasPermission('delivery.deliveryAgent.range.create'))
                                            @include('DeliveryAgents::admin.DeliveryAgents.view.Ranges.create')
                                        @endif

                                    </div>
                                    </x-slot>

                                    <x-slot:content>

                                        <template v-if="deliveryagent.ranges.length">
                                            <div
                                                class="grid gap-y-2.5"
                                                v-for="(range, index) in deliveryagent.ranges"
                                            >
                                                <p class="text-sm mt-3 text-gray-600 dark:text-gray-300 font-medium">
                                                    @{{ getCountryName(range.state_area.country_code) }}
                                                    <span
                                                        class="mx-1 text-gray-400">/</span>
                                                    @{{ getStateName(range.state_area.country_code,
                                                    range.state_area.state_code) }}
                                                    <span class="mx-1 text-gray-400">/</span>
                                                    @{{ range.state_area.area_name }}
                                                </p>

                                                <div class=" flex items-center gap-2.5">
                                                    @include('DeliveryAgents::admin.DeliveryAgents.view.Ranges.edit')

                                                    @if (bouncer()->hasPermission('delivery.deliveryAgent.range.delete'))
                                                        <button
                                                            class="text-red-600 hover:underline transition-all cursor-pointer"
                                                            @click="deleteRange(range.id)"
                                                        >

                                                            @lang('deliveryAgent::app.range.view.delete-btn')

                                                        </button>
                                                    @endif
                                                </div>

                                                <span
                                                    v-if="index != deliveryagent?.ranges.length - 1"
                                                    class="mb-4 mt-4 block w-full border-b dark:border-gray-800"
                                                ></span>

                                            </div>
                                        </template>


                                        <template v-else>
                                            <!-- Empty Range Container -->
                                            <div class="flex items-center gap-5 py-2.5">
                                                <img
                                                    src="{{ bagisto_asset('images/settings/address.svg') }}"
                                                    class="h-20 w-20 dark:mix-blend-exclusion dark:invert"
                                                />

                                                <div class="flex flex-col gap-1.5">
                                                    <p class="text-base font-semibold text-gray-400">
                                                        @lang('deliveryAgent::app.range.view.empty-title')

                                                    </p>

                                                    <p class="text-gray-400">
                                                        @lang('deliveryAgent::app.range.view.empty-description')

                                                    </p>
                                                </div>
                                            </div>
                                        </template>
                                        </x-slot>
                            </x-admin::accordion>
                        </template>
                    </div>

                </div>

        </script>



        <script type="module">
            app.component('v-delivery-agente-view', {
                template: '#v-delivery-agent-view-template',
                data() {
                    return {
                        deliveryagent: @json($deliveryAgent),
                        countries: window.countries || {},
                        countryStates:window.countryStates||{},
                        isUpdating: {},
                    };
                },
                methods: {
                    updateDeliveyAgent(data) {
                        this.deliveryagent = {
                            ...this.deliveryagent,
                            ...data.deliveryagent,
                        };
                    },
                     rangeCreated(range) {
                         this.deliveryagent.ranges.push({
                             ...range,
                         });
                    },
                    rangeUpdated(updatedRange) {
                        const index = this.deliveryagent.ranges.findIndex(r => r.id === updatedRange.id);

                        if (index !== -1) {
                            Object.assign(this.deliveryagent.ranges[index], updatedRange);
                        }
                    },
                    deleteRange(id) {
                        this.$emitter.emit('open-confirm-modal', {
                            message: '@lang('deliveryAgent::app.range.view.range-delete-confirmation')',

                            agree: () => {
                                this.$axios.post(`{{ route('admin.range.delete', '') }}/${id}`)
                                    .then((response) => {
                                        this.$emitter.emit('add-flash', {
                                            type: 'success',
                                            message: response.data.message
                                        });
                                        this.deliveryagent.ranges = this.deliveryagent.ranges.filter(range => range.id !== id);
                                    })
                                    .catch((error) => {
                                    });
                            },
                        });
                    },
                    getCountryName(code) {
                        return this.countries[code] || code;
                    },

                    getStateName(countryCode, stateCode) {
                        const states = this.countryStates[countryCode] || [];
                        const state = states.find(s => s.code === stateCode);
                        return state ? state.default_name : stateCode;
                    },


                },


            })
        </script>

    @endpushonce

</x-admin::layouts>
