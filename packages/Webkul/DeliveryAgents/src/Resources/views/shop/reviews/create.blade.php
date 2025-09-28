@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-delivery-agent-review-form-template"
    >
        <div class="container max-1180:px-5">


            <!-- Review Form Modal -->
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit, reset, setErrors }"
                as="div"
            >
                <form id="deliveryAgentReviewForm" @submit="handleSubmit($event, create)" class="space-y-6">
                <x-shop::modal ref="DeliveryAgentReviewCreateModal">
                <x-slot:header>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('deliveryAgent::app.shop.customer.account.orders.view.review_form.title') }}</h2>
                </x-slot:header>

                <x-slot:content>

                            <!-- Rating Section -->
                            <div class="space-y-4">
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="required text-lg font-medium">
                                        {{ __('deliveryAgent::app.shop.customer.account.orders.view.review_form.rating_label') }}
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
                                        {{ __('deliveryAgent::app.shop.customer.account.orders.view.review_form.comment_label') }}
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="textarea"
                                        name="comment"
                                        rules="required"
                                        :value="old('comment')"
                                        :label="__('deliveryAgent::app.shop.customer.account.orders.view.review_form.comment_label')"
                                        :placeholder="__('deliveryAgent::app.shop.customer.account.orders.view.review_form.comment_placeholder')"
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
                    <div class="flex justify-center">
                        <x-shop::button
                            button-type="submit"
                            class="primary-button px-8 py-3"
                            :title="__('deliveryAgent::app.shop.customer.account.orders.view.review_form.submit_button')"
                            ::loading="isLoading"
                            ::disabled="isLoading"
                        />
                    </div>
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
                },
                openModal() {
                    this.$refs.DeliveryAgentReviewCreateModal.open();
                }
            }
        });
    </script>
@endPushOnce
