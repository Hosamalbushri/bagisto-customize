@if($order->is_delivered && !$order->hasReview())
    <v-create-delivery-agent-review-form
    >
    </v-create-delivery-agent-review-form>
@endif


@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-delivery-agent-review-form-template"
    >
        <div class="container max-1180:px-5">
            @if(!empty($order->delivery_agent_id))
                <div class="mb-6 text-center">
                    <button
                        class="inline-flex items-center gap-2 rounded-xl border border-navyBlue bg-white px-6 py-3 text-center text-base font-medium text-navyBlue transition-colors hover:bg-navyBlue"
                        @click="$refs.DeliveryAgentReviewCreateModal.open()"
                    >
                        <span class="icon-star text-lg"></span>
                        تقييم المندوب
                    </button>
                </div>
            @endif


            <!-- Review Form Modal -->
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit, reset, setErrors }"
                as="div"
            >
                <form id="deliveryAgentReviewForm" @submit="handleSubmit($event, create)" class="space-y-6">
                <x-shop::modal ref="DeliveryAgentReviewCreateModal">
                <x-slot:header>
                    <h2 class="text-2xl font-bold text-gray-900">تقييم المندوب</h2>
                </x-slot:header>

                <x-slot:content>

                            <!-- Rating Section -->
                            <div class="space-y-4">
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="required text-lg font-medium">
                                        تقييم المندوب
                                    </x-shop::form.control-group.label>

                                    <div class="flex items-center gap-2">
                                        <span
                                            class="icon-star-fill cursor-pointer text-3xl transition-colors duration-200"
                                            role="presentation"
                                            v-for="rating in [1,2,3,4,5]"
                                            :class="appliedRatings >= rating ? 'text-amber-500' : 'text-gray-300'"
                                            @click="appliedRatings = rating"
                                        ></span>
                                    </div>

                                    <v-field
                                        type="hidden"
                                        name="rating"
                                        v-model="appliedRatings"
                                    ></v-field>

                                    <x-shop::form.control-group.error control-name="rating" />
                                </x-shop::form.control-group>

                                <!-- Review Comment -->
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="required">
                                        تعليقك على المندوب
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="textarea"
                                        name="comment"
                                        rules="required"
                                        :value="old('comment')"
                                        :label="'تعليقك على المندوب'"
                                        :placeholder="'اكتب تعليقك على أداء المندوب...'"
                                        rows="6"
                                        class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                    />
                                    <x-shop::form.control-group.error control-name="comment" />
                                </x-shop::form.control-group>
                                <x-shop::form.control-group.control
                                    type="hidden"
                                    name="customer_id"
                                    :value="auth()->user()->id"
                                />
                                <x-shop::form.control-group.control
                                    type="hidden"
                                    name="order_id"
                                    :value="$order->id"
                                />
                                <x-shop::form.control-group.control
                                    type="hidden"
                                    name="delivery_agent_id"
                                    :value="$order->delivery_agent_id"
                                />

                            </div>
                </x-slot:content>

                <x-slot:footer>
                    <x-shop::button
                        button-type="submit"
                        class="primary-button"
                        :title="trans('deliveryAgent::app.range.create.create-btn')"
                        ::loading="isLoading"
                        ::disabled="isLoading"
                    />
                </x-slot:footer>
            </x-shop::modal>
                </form>
            </x-shop::form>
        </div>
    </script>


    <script type="module">
        app.component('v-create-delivery-agent-review-form', {
            template: '#v-delivery-agent-review-form-template',
            data() {
                return {
                    isLoading: false,
                    appliedRatings: 5,
                };
            },
            methods: {
                create(params,{ resetForm, setErrors }) {
                    this.isLoading = true;
                    params.rating = this.appliedRatings;
                    this.$axios.post("{{ route('admin.review.create') }}", params)
                        .then((response) => {
                            this.$refs.DeliveryAgentReviewCreateModal.close();
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            this.$emit('range-created', response.data.data);
                            resetForm();
                            this.isLoading = false;
                            this.appliedRatings = 5; // Reset rating
                            window.location.reload();
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
                }
            }
        });
    </script>
@endPushOnce
