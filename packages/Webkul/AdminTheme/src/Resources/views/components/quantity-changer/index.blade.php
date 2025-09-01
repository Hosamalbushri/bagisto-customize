@props([
    'name'  => '',
    'value' => 1,
])

<v-quantity-changer
    {{ $attributes->merge(['class' => 'flex items-center border dark:border-gray-300']) }}
    name="{{ $name }}"
    value="{{ $value }}"
>
</v-quantity-changer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-quantity-changer-template"
    >
        <div class="inline-flex items-center space-x-2 bg-white dark:bg-gray-800 shadow-sm p-1 rounded-lg">
            <span
                class="acma-icon-minus1 text-xl font-bold text-gray-700 dark:text-gray-300 transition-colors px-2"
                @click="decrease"
            >

            </span>

            <input
                type="text"
                min="1"
                class="w-12 text-center text-lg font-medium text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800  border-gray-300 dark:border-gray-600 focus:outline-none"
                v-model="quantity"
                @change="validateQuantity"
            />

            <span
                class="acma-icon-plus2 text-xl font-bold text-gray-700 dark:text-gray-300 transition-colors px-2"
                @click="increase"
            >

            </span>

            <v-field
                type="hidden"
                :name="name"
                v-model="quantity"
            ></v-field>
        </div>
    </script>

    <script type="module">
        app.component("v-quantity-changer", {
            template: '#v-quantity-changer-template',

            props:['name', 'value'],

            data() {
                return  {
                    quantity: this.value,
                }
            },

            watch: {
                value() {
                    this.quantity = this.value;
                },
            },

            methods: {
                increase() {
                    this.quantity = Number(this.quantity) + 1; // زيادة العدد
                    this.emitChange(); // إرسال القيمة الجديدة
                },

                decrease() {
                    if (this.quantity > 1) {
                        this.quantity = Number(this.quantity) - 1; // إنقاص العدد
                        this.emitChange();
                    }
                },

                emitChange() {
                    this.$emit('change', this.quantity);
                },

                validateQuantity() {
                    const number = Number(this.quantity);
                    if (isNaN(number) || number < 1) {
                        this.quantity = 1;
                    }
                    this.emitChange();
                },
            }
        });
    </script>
@endpushOnce
