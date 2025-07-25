<x-admin::layouts>

    <v-delivery-agente-view>
        <!-- Shimmer Effect -->
    </v-delivery-agente-view>
     @pushOnce('scripts')

        <script>
            window.countries = @json(core()->countries()->pluck('name', 'code'));
            window.countryStates = @json(core()->groupedStatesByCountries());
        </script>

        <script
            type="text/x-template"
            id="v-delivery-agent-view-template"
        >
            <x-slot:title>
                @lang('deliveryagent::app.deliveryagents.view.title')
                </x-slot>

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
                                    v-text="`@lang('deliveryagent::app.deliveryagents.view.title') : ${deliveryagent.first_name} ${deliveryagent.last_name}`"
                                ></h1>

                                <!-- status -->
                                <span
                                    v-if="deliveryagent.status"
                                    class="label-active mx-1.5 text-sm">
                                     @lang('deliveryagent::app.deliveryagents.view.active')
                                </span>
                                <span
                                    v-else
                                    class="label-canceled mx-1.5 text-sm">
                                      @lang('deliveryagent::app.deliveryagents.view.inactive')
                                </span>
                            </template>
                        </div>

                        <!-- back button -->
                        <a
                            href="{{ route('admin.deliveryagents.index') }}"
                            class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800">
                            @lang('deliveryagent::app.deliveryagents.view.back-btn')
                        </a>
                    </div>
                </div>
                <!-- Filters -->
                <div class="mt-7 flex flex-wrap items-center gap-x-1 gap-y-2">
                    <!-- Account Delete button -->
                    @if (bouncer()->hasPermission('delivery.deliveryAgent.delete'))
                        <div
                            class="inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-red-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-red-800"
                            @click="$emitter.emit('open-confirm-modal', {
                            message: '@lang('admin::app.customers.customers.view.account-delete-confirmation')',

                            agree: () => {
                                this.$refs['delete-account'].submit()
                            }
                        })"
                        >
                            <span class="acma-icon-bin text-red-600"></span>
                            @lang('deliveryagent::app.deliveryagents.view.delete-btn')


                            <!-- Delete Customer Account -->
                            <form
                                {{--                                method="post"--}}
                                {{--                                action="{{ route('admin.customers.customers.delete', $customer->id) }}"--}}
                                {{--                                ref="delete-account"--}}
                            >
                                @csrf
                            </form>
                        </div>
                    @endif
                </div>
                <!-- Content -->
                <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
                    <!-- Left Component -->
                    <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                        {{--                        @include('deliveryagents::admin.Countries.States.index')--}}

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
                                            @lang('deliveryagent::app.deliveryagents.view.delivery-agent')
                                        </p>

                                        <!--Delivery Agents Edit Component -->
                                        @include('deliveryagents::admin.deliveryagents.view.edit')


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
                                            @{{ "@lang('deliveryagent::app.deliveryagents.view.email')
                                            ".replace(':email', deliveryagent.email ?? 'N/A') }}
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @{{ "@lang('deliveryagent::app.deliveryagents.view.phone')
                                            ".replace(':phone', deliveryagent.phone ?? 'N/A') }}
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @{{ "@lang('deliveryagent::app.deliveryagents.view.gender')
                                            ".replace(':gender', deliveryagent.gender ?? 'N/A') }}
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @{{ "@lang('deliveryagent::app.deliveryagents.view.date-of-birth')
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
                                            @{{ "@lang('deliveryagent::app.range.view.count')".replace(':count',deliveryagent.ranges.length) }}
                                        </p>

                                        <!-- Ranges Create component -->
                                        @include('deliveryagents::admin.deliveryagents.view.Ranges.create')

                                    </div>
                                    </x-slot>

                                    <x-slot:content>

                                        <template v-if="deliveryagent.ranges.length">
                                            <div
                                                class="grid gap-y-2.5"
                                                v-for="(range, index) in deliveryagent.ranges"
                                            >
                                                <p class="text-sm mt-3 text-gray-600 dark:text-gray-300 font-medium">
                                                    @{{ getCountryName(range.country) }} <span class="mx-1 text-gray-400">/</span>
                                                    @{{ getStateName(range.country, range.state) }} <span class="mx-1 text-gray-400">/</span>
                                                    @{{ range.area_name }}
                                                </p>

                                                <!-- أزرار الأكشن -->
                                                <div class=" flex items-center gap-2.5">
                                                    <!-- تعديل -->
                                                     @include('deliveryagents::admin.deliveryagents.view.Ranges.edit')

                                                    <!-- حذف -->
                                                    @if (bouncer()->hasPermission('customers.addresses.delete'))
                                                        <button
                                                            class="text-red-600 hover:underline transition-all cursor-pointer"
                                                            @click="deleteRange(range.id)"
                                                        >

                                                            @lang('deliveryagent::app.range.view.delete-btn')

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
                                                        @lang('deliveryagent::app.range.view.empty-title')

                                                    </p>

                                                    <p class="text-gray-400">
                                                        @lang('deliveryagent::app.range.view.empty-description')

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
                        countryStates: window.countryStates || {},
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
                    async rangeCreated(range) {
                        try {
                            // أعد جلب البيانات من الخادم للتأكد من التحديث
                            const response = await this.$axios.get(`/admin/delivery-agents/view/${this.deliveryagent.id}`);
                            this.deliveryagent = response.data.data;
                        } catch (error) {
                            console.error(error);
                            // كبديل، أضف النطاق يدوياً مع التأكد من التفاعلية
                            this.$set(this.deliveryagent, 'ranges', [
                                ...this.deliveryagent.ranges,
                                {
                                    ...range,
                                    pivot: range.pivot
                                }
                            ]);
                        }
                    },
                    getCountryName(code) {
                        return this.countries[code] || code;
                    },

                    getStateName(countryCode, stateCode) {
                        const states = this.countryStates[countryCode] || [];
                        const state = states.find(s => s.code === stateCode);
                        return state ? state.default_name : stateCode;
                    },

                    rangeUpdated(updatedRange)
                    {
                        this.deliveryagent.ranges =this.deliveryagent.ranges.map(range => {
                            if (range.id === updatedRange.id) {
                                return {
                                    ...updatedRange,
                                };
                            }

                            return range;
                        });

                    }


                },



            })
        </script>

    @endpushonce

</x-admin::layouts>
