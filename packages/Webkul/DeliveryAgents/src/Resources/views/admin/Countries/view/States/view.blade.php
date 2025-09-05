<x-admin::layouts>

    <v-state-view>

    </v-state-view>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-state-template"
        >
            <x-slot:title>
                @php
                    $label = trans('deliveryAgent::app.country.state.view.title');
                @endphp

                {{ isset($state) && !empty($state->default_name) ? ($label . ': ' . $state->default_name) : $label }}
            </x-slot:title>
                <div class="grid">
                    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
                        <div class="flex items-center gap-2.5">
                            <template
                                v-if="! state"
                                class="flex gap-5"
                            >
                                <p class="shimmer w-32 p-2.5"></p>

                                <p class="shimmer w-14 p-2.5"></p>
                            </template>

                            <template v-else>
                                <h1
                                    v-if="state"
                                    class="text-xl font-bold leading-6 text-gray-800 dark:text-white"
                                    @lang('deliveryAgent::deliveryagent::app.country.view.title')
                                    v-text="`@lang('deliveryAgent::app.country.state.view.title'):  ${state.default_name}`"                                ></h1>
                            </template>
                        </div>

                        <!-- Back Button -->
                        <a
                            href="{{ route('admin.country.edit',$state->country_id) }}"
                            class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                        >
                            @lang('deliveryAgent::app.country.state.view.back-btn')
                        </a>
                    </div>
                </div>


                <!-- Content -->
                <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
                    <!-- Left Component -->
                    <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                        @if (bouncer()->hasPermission('delivery.countries.states.area'))
                            @include('DeliveryAgents::admin.Countries.view.States.Areas.index')
                        @endif

                    </div>
                    <!-- Right Component -->
                    <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                        <template v-if="! state">
                            <x-admin::shimmer.accordion class="h-[271px] w-[360px]"/>
                        </template>

                        <template v-else>
                            <x-admin::accordion>
                                <x-slot:header>
                                    <div class="flex w-full">
                                        <p class="w-full p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                                            @lang('deliveryAgent::app.country.state.view.state')
                                        </p>

                                        <!--State Edit Component -->
                                        @include('DeliveryAgents::admin.Countries.view.States.edit')
                                    </div>
                                </x-slot:header>

                                <x-slot:content>
                                    <template v-if="state">
                                        <div class="grid gap-y-2.5">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('deliveryAgent::app.country.state.view.name')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="name"
                                                    v-model="state.default_name"
                                                    readonly
                                                    disabled
                                                />
                                            </x-admin::form.control-group>

                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('deliveryAgent::app.country.state.view.code')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="code"
                                                    v-model="state.code"
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
            app.component('v-state-view', {
                template: '#v-state-template',
                data() {
                    return {
                        state: @json($state),

                        isUpdating: {},
                    };
                },
                methods: {

                    updateState(data) {
                        this.state = {
                            ...this.state,
                            ...data.state,
                        };
                    },
                    onAreaCreated(area)
                    {
                        this.$refs.areaDatagrid.get();
                    }

                }



            })
        </script>

    @endpushonce

</x-admin::layouts>
