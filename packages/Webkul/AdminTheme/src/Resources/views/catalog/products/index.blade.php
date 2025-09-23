{{-- ==========================================
    Products Index Page - Admin Panel
    ========================================== --}}

<x-admin::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('admin::app.catalog.products.index.title')
    </x-slot>

    {{-- Header Section --}}
    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        {{-- Page Title --}}
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.catalog.products.index.title')
        </p>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-x-2.5">
            {{-- Export Button --}}
            <x-admin::datagrid.export :src="route('admin.catalog.products.index')" />

            {{-- Before Create Event --}}
            {!! view_render_event('bagisto.admin.catalog.products.create.before') !!}

            {{-- Create Product Button --}}
            @if (bouncer()->hasPermission('catalog.products.create'))
                <v-create-product-form>
                    <button
                        type="button"
                        class="primary-button"
                    >
                        @lang('admin::app.catalog.products.index.create-btn')
                    </button>
                </v-create-product-form>
            @endif

            {{-- After Create Event --}}
            {!! view_render_event('bagisto.admin.catalog.products.create.after') !!}
        </div>
    </div>

    {{-- Before List Event --}}
    {!! view_render_event('bagisto.admin.catalog.products.list.before') !!}

    {{-- Products DataGrid --}}
    <x-admin::datagrid
        :src="route('admin.catalog.custom.products.index')"
        :isMultiRow="true"
    >
        {{-- DataGrid Header Template --}}
        @php
            $hasPermission = bouncer()->hasPermission('catalog.products.edit') || bouncer()->hasPermission('catalog.products.delete');
        @endphp

        <template #header="{
            isLoading,
            available,
            applied,
            selectAll,
            sort,
            performAction
        }">
            {{-- Loading CountryState --}}
            <template v-if="isLoading">
                <x-admin::shimmer.datagrid.table.head :isMultiRow="true" />
            </template>

            {{-- Header Content --}}
            <template v-else>
                <div class="row grid grid-cols-[2fr_1fr_1fr] grid-rows-1 items-center border-b px-4 py-2.5 dark:border-gray-800">
                    <div
                        class="flex select-none items-center gap-2.5"
                        v-for="(columnGroup, index) in [
                            ['name', 'sku', 'attribute_family'],
                            ['base_image', 'price', 'quantity', 'product_id'],
                            ['status', 'category_name', 'type']
                        ]"
                    >
                        {{-- Mass Action Checkbox --}}
                        @if ($hasPermission)
                            <label
                                class="flex w-max cursor-pointer select-none items-center gap-1"
                                for="mass_action_select_all_records"
                                v-if="! index"
                            >
                                <input
                                    type="checkbox"
                                    name="mass_action_select_all_records"
                                    id="mass_action_select_all_records"
                                    class="peer hidden"
                                    :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
                                    @change="selectAll"
                                >

                                <span
                                    class="icon-uncheckbox cursor-pointer rounded-md text-2xl"
                                    :class="[
                                        applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-checked peer-checked:active-checkbox' : (
                                            applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-checkbox-partial peer-checked:active-checkbox' : ''
                                        ),
                                    ]"
                                >
                                </span>
                            </label>
                        @endif

                        {{-- Column Headers --}}
                        <p class="text-gray-600 dark:text-gray-300">
                            <span class="[&>*]:after:content-['_/_']">
                                <template v-for="column in columnGroup">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'font-medium text-gray-800 dark:text-white': applied.sort.column == column,
                                            'cursor-pointer hover:text-gray-800 dark:hover:text-white': available.columns.find(columnTemp => columnTemp.index === column)?.sortable,
                                        }"
                                        @click="
                                            available.columns.find(columnTemp => columnTemp.index === column)?.sortable ? sort(available.columns.find(columnTemp => columnTemp.index === column)): {}
                                        "
                                    >
                                        @{{ available.columns.find(columnTemp => columnTemp.index === column)?.label }}
                                    </span>
                                </template>
                            </span>

                            {{-- Sort Icon --}}
                            <i
                                class="align-text-bottom text-base text-gray-800 dark:text-white ltr:ml-1.5 rtl:mr-1.5"
                                :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                v-if="columnGroup.includes(applied.sort.column)"
                            ></i>
                        </p>
                    </div>
                </div>
            </template>
        </template>

        {{-- DataGrid Body Template --}}
        <template #body="{
            isLoading,
            available,
            applied,
            selectAll,
            sort,
            performAction
        }">
            {{-- Loading CountryState --}}
            <template v-if="isLoading">
                <x-admin::shimmer.datagrid.table.body :isMultiRow="true" />
            </template>

            {{-- Data Rows --}}
            <template v-else>
                <div
                    class="row grid grid-cols-[2fr_1fr_1fr] grid-rows-1 gap-1.5 border-b px-4 py-2.5 transition-all hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950"
                    v-for="record in available.records"
                >
                    {{-- Product Info Column (Name, SKU, Attribute Family) --}}
                    <div class="flex gap-2.5">
                        {{-- Mass Action Checkbox --}}
                        @if ($hasPermission)
                            <input
                                type="checkbox"
                                :name="`mass_action_select_record_${record.product_id}`"
                                :id="`mass_action_select_record_${record.product_id}`"
                                :value="record.product_id"
                                class="peer hidden"
                                v-model="applied.massActions.indices"
                            >

                            <label
                                class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:active-checkbox"
                                :for="`mass_action_select_record_${record.product_id}`"
                            ></label>
                        @endif

                        {{-- Product Details --}}
                        <div class="flex flex-col gap-1.5">
                            {{-- Product Name --}}
                            <p class="break-all text-base font-semibold text-gray-800 dark:text-white">
                                @{{ record.name }}
                            </p>

                            {{-- SKU --}}
                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('admin::app.catalog.products.index.datagrid.sku-value')".replace(':sku', record.sku) }}
                            </p>

                            {{-- Attribute Family --}}
                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('admin::app.catalog.products.index.datagrid.attribute-family-value')".replace(':attribute_family', record.attribute_family) }}
                            </p>
                        </div>
                    </div>

                    {{-- Product Media & Pricing Column --}}
                    <div class="flex gap-1.5">
                        {{-- Product Image --}}
                        <div class="relative">
                            <template v-if="record.base_image">
                                <img
                                    class="max-h-[65px] min-h-[65px] min-w-[65px] max-w-[65px] rounded"
                                    :src=`{{ Storage::url('') }}${record.base_image}`
                                    :alt="record.name"
                                />

                                {{-- Image Count Badge --}}
                                <span class="absolute bottom-px rounded-full bg-darkPink px-1.5 text-xs font-bold leading-normal text-white ltr:left-px rtl:right-px">
                                    @{{ record.images_count }}
                                </span>
                            </template>

                            {{-- Placeholder Image --}}
                            <template v-else>
                                <div class="relative h-[60px] max-h-[60px] w-full max-w-[60px] rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert">
                                    <img
                                        src="{{ bagisto_asset('images/product-placeholders/front.svg')}}"
                                        alt="No Image"
                                    >

                                    <p class="absolute bottom-1.5 w-full text-center text-[6px] font-semibold text-gray-400">
                                        @lang('admin::app.catalog.products.index.datagrid.product-image')
                                    </p>
                                </div>
                            </template>
                        </div>

                        {{-- Price & Stock Info --}}
                        <div class="flex flex-col gap-1.5">
                            {{-- Product Price --}}
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @{{ $admin.formatPrice(record.price) }}
                            </p>

                            {{-- Stock Quantity --}}
                            {{-- Parent Product Types (No Stock) --}}
                            <div v-if="['configurable', 'bundle', 'grouped', 'booking'].includes(record.type)">
                                <p class="text-gray-600 dark:text-gray-300">
                                    <span class="text-red-600">N/A</span>
                                </p>
                            </div>

                            {{-- Simple Product Types (With Stock) --}}
                            <div v-else>
                                {{-- In Stock --}}
                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                    v-if="record.quantity > 0"
                                >
                                    <span class="text-green-600">
                                        @{{ "@lang('admin::app.catalog.products.index.datagrid.qty-value')".replace(':qty', record.quantity) }}
                                    </span>
                                </p>

                                {{-- Out of Stock --}}
                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                    v-else
                                >
                                    <span class="text-green-600">
                                        @{{ "@lang('admin::app.catalog.products.index.datagrid.qty-value')".replace(':qty', 0) }}
                                    </span>
                                </p>
                            </div>

                            {{-- Product ID --}}
                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('admin::app.catalog.products.index.datagrid.id-value')".replace(':id', record.product_id) }}
                            </p>
                        </div>
                    </div>

                    {{-- Product Status & Actions Column --}}
                    <div class="flex items-center justify-between gap-x-4">
                        {{-- Product Status & Info --}}
                        <div class="flex flex-col gap-1.5">
                            {{-- Product Status --}}
                            <p v-html="record.status"></p>

                            {{-- Category Name --}}
                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ record.category_name ?? 'N/A' }}
                            </p>

                            {{-- Product Type --}}
                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ record.type }}
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <p
                            class="flex items-center gap-1.5"
                            v-if="available.actions.length"
                        >
                            <span
                                class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                :class="action.icon"
                                v-text="! action.icon ? action.title : ''"
                                v-for="action in record.actions"
                                @click="performAction(action)"
                            >
                            </span>
                        </p>
                    </div>
                </div>
            </template>
        </template>
    </x-admin::datagrid>

    {{-- After List Event --}}
    {!! view_render_event('bagisto.admin.catalog.products.list.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-product-form-template"
        >
            <div>
                <!-- Product Create Button -->
                @if (bouncer()->hasPermission('catalog.products.create'))
                    <button
                        type="button"
                        class="primary-button"
                        @click="$refs.productCreateModal.toggle()"
                    >
                        @lang('admin::app.catalog.products.index.create-btn')
                    </button>
                @endif

                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, create)">
                        {{-- Auto-Generated Values Configuration --}}
                        @php
                            // Get configuration values
                            $autoGenerateSku = core()->getConfigData('catalog.products.create.auto_generate_sku');
                            $enableDefaultProductType = core()->getConfigData('catalog.products.create.enable_default_product_type');
                            $defaultProductType = core()->getConfigData('catalog.products.create.default_product_type');
                            $skuPrefix = core()->getConfigData('catalog.products.create.sku_prefix') ?: '';
                            $skuLength = core()->getConfigData('catalog.products.create.sku_length') ?: 6;
                            $enableDefaultAttributeFamily = core()->getConfigData('catalog.products.create.enable_default_attribute_family');
                            $defaultAttributeFamilyId = core()->getConfigData('catalog.products.create.default_attribute_family_id');

                            // Generate unique SKU if auto-generate is enabled
                            $generatedSku = null;
                            if ($autoGenerateSku) {
                                if (empty($skuPrefix)) {
                                    // Generate numeric SKU without prefix
                                    $lastSku = \Webkul\Product\Models\Product::whereRaw('sku REGEXP "^[0-9]+$"')
                                        ->orderBy('id', 'desc')
                                        ->first();

                                    $nextNumber = $lastSku ? (int) $lastSku->sku + 1 : 1;
                                    $generatedSku = str_pad($nextNumber, $skuLength, '0', STR_PAD_LEFT);
                                } else {
                                    // Generate SKU with prefix
                                    $lastSku = \Webkul\Product\Models\Product::where('sku', 'like', $skuPrefix . '%')
                                        ->orderBy('id', 'desc')
                                        ->first();

                                    if ($lastSku) {
                                        $lastNumber = (int) substr($lastSku->sku, strlen($skuPrefix));
                                        $nextNumber = $lastNumber + 1;
                                    } else {
                                        $nextNumber = 1;
                                    }

                                    $generatedSku = $skuPrefix . str_pad($nextNumber, $skuLength, '0', STR_PAD_LEFT);
                                }
                            }
                        @endphp


                        {{-- Hidden Fields --}}
                        @if($enableDefaultProductType && $defaultProductType)
                            <input type="hidden" name="type" value="{{ $defaultProductType }}">
                        @endif

                        {{-- Product Create Modal --}}
                        <x-admin::modal ref="productCreateModal">
                            {{-- Modal Header --}}
                            <x-slot:header>
                                <p
                                    class="text-lg font-bold text-gray-800 dark:text-white"
                                    v-if="! attributes.length"
                                >
                                    @lang('admin::app.catalog.products.index.create.title')
                                </p>

                                <p
                                    class="text-lg font-bold text-gray-800 dark:text-white"
                                    v-else
                                >
                                    @lang('admin::app.catalog.products.index.create.configurable-attributes')
                                </p>
                            </x-slot>

                            {{-- Modal Content --}}
                            <x-slot:content>
                                {{-- General Form Controls --}}
                                <div v-show="! attributes.length">
                                    {{-- Before General Controls Event --}}
                                    {!! view_render_event('bagisto.admin.catalog.products.create_form.general.controls.before') !!}

                                    {{-- Product Type Selection --}}
                                    @if($enableDefaultProductType && $defaultProductType)
                                        {{-- Display Selected Product Type --}}
                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.catalog.products.index.create.type')
                                            </x-admin::form.control-group.label>

                                            <div class="px-3 py-2  border border-green-200 rounded-md">
                                                <span class=" dark:text-white font-mono">
                                                    @lang('product::app.type.' . $defaultProductType)
                                                </span>
                                                <small class="block text-green-600 dark:!text-white text-xs mt-1 ">
                                                    @lang('adminTheme::app.configuration.index.catalog.products.create.default-product-type-selected')
                                                </small>
                                            </div>
                                            <x-admin::form.control-group.control
                                                type="hidden"
                                                name="type"
                                                value="{{ $defaultProductType }}"
                                            >
                                            </x-admin::form.control-group.control>
                                        </x-admin::form.control-group>
                                    @else
                                        {{-- Product Type Dropdown --}}
                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.catalog.products.index.create.type')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="type"
                                                rules="required"
                                                :label="trans('admin::app.catalog.products.index.create.type')"
                                            >
                                                @foreach(config('product_types') as $key => $type)
                                                    <option value="{{ $key }}">
                                                        @lang($type['name'])
                                                    </option>
                                                @endforeach
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error control-name="type" />
                                        </x-admin::form.control-group>
                                    @endif

                                    {{-- Attribute Family Selection --}}
                                    @if($enableDefaultAttributeFamily && $defaultAttributeFamilyId)
                                        {{-- Auto-select if default family is configured --}}
                                        @php
                                            $defaultFamily = $families->where('id', $defaultAttributeFamilyId)->first();
                                        @endphp
                                        @if($defaultFamily)
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.catalog.products.index.create.family')
                                                </x-admin::form.control-group.label>

                                                <div class="px-3 py-2 border border-green-200 rounded-md">
                                                    <span class="dark:text-white font-mono">
                                                        {{ $defaultFamily->name }}
                                                    </span>
                                                    <small class="block text-green-600 dark:!text-white text-xs mt-1">
                                                        @lang('adminTheme::app.configuration.index.catalog.products.create.auto-selected-family')
                                                    </small>
                                                </div>
                                                <x-admin::form.control-group.control
                                                    type="hidden"
                                                    name="attribute_family_id"
                                                    value="{{ $defaultFamily->id }}"
                                                >
                                                </x-admin::form.control-group.control>
                                            </x-admin::form.control-group>
                                        @else
                                            {{-- Fallback to dropdown if default family not found --}}
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label class="required">
                                                    @lang('admin::app.catalog.products.index.create.family')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="attribute_family_id"
                                                    rules="required"
                                                    :label="trans('admin::app.catalog.products.index.create.family')"
                                                >
                                                    @foreach($families as $family)
                                                        <option value="{{ $family->id }}">
                                                            {{ $family->name }}
                                                        </option>
                                                    @endforeach
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error control-name="attribute_family_id" />
                                            </x-admin::form.control-group>
                                        @endif
                                    @elseif(count($families) == 1)
                                        {{-- Auto-select if only one family exists --}}
                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.catalog.products.index.create.family')
                                            </x-admin::form.control-group.label>

                                            <div class="px-3 py-2 border border-green-200 rounded-md">
                                                <span class="dark:text-white font-mono">
                                                    {{ $families->first()->name }}
                                                </span>
                                                <small class="block text-green-600 dark:!text-white text-xs mt-1">
                                                    @lang('adminTheme::app.configuration.index.catalog.products.create.auto-selected-family')
                                                </small>
                                            </div>
                                            <x-admin::form.control-group.control
                                                type="hidden"
                                                name="attribute_family_id"
                                                value="{{ $families->first()->id }}"
                                            >
                                            </x-admin::form.control-group.control>
                                        </x-admin::form.control-group>
                                    @else
                                        {{-- Show dropdown if multiple families exist --}}
                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.catalog.products.index.create.family')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="attribute_family_id"
                                                rules="required"
                                                :label="trans('admin::app.catalog.products.index.create.family')"
                                            >
                                                @foreach($families as $family)
                                                    <option value="{{ $family->id }}">
                                                        {{ $family->name }}
                                                    </option>
                                                @endforeach
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error control-name="attribute_family_id" />
                                        </x-admin::form.control-group>
                                    @endif

                                    {{-- SKU Configuration --}}
                                    @if($autoGenerateSku)
                                        {{-- Auto-Generated SKU Display --}}
                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.catalog.products.index.create.sku')
                                            </x-admin::form.control-group.label>

                                            <div class="flex items-center gap-2">
                                                <div class="flex-1 px-3 py-2 border border-blue-200 rounded-md">
                                                    <span class=" dark:text-white font-mono">{{ $generatedSku }}</span>
                                                    <small class="block text-blue-600 dark:text-white text-xs mt-1">
                                                        @lang('adminTheme::app.configuration.index.catalog.products.create.auto-generated-sku')
                                                    </small>
                                                    @if($generatedSku)
                                                        <x-admin::form.control-group.control
                                                            type="hidden"
                                                            name="sku"
                                                            value="{{ $generatedSku }}"
                                                        />
                                                    @endif
                                                </div>
                                            </div>
                                        </x-admin::form.control-group>
                                    @else
                                        {{-- Manual SKU Input --}}
                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.catalog.products.index.create.sku')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="sku"
                                                ::rules="{ required: true, regex: /^[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/ }"
                                                :label="trans('admin::app.catalog.products.index.create.sku')"
                                            />

                                            <x-admin::form.control-group.error control-name="sku" />
                                        </x-admin::form.control-group>
                                    @endif

                                    {{-- After General Controls Event --}}
                                    {!! view_render_event('bagisto.admin.catalog.products.create_form.general.controls.after') !!}
                                </div>

                                {{-- Configurable Attributes Section --}}
                                <div v-show="attributes.length">
                                    {{-- Before Attributes Controls Event --}}
                                    {!! view_render_event('bagisto.admin.catalog.products.create_form.attributes.controls.before') !!}

                                    {{-- Attribute Options --}}
                                    <div
                                        class="mb-2.5"
                                        v-for="attribute in attributes"
                                    >
                                        <label
                                            class="block text-xs font-medium leading-6 text-gray-800 dark:text-white"
                                            v-text="attribute.name"
                                        >
                                        </label>

                                        <div class="flex min-h-[38px] flex-wrap gap-1 rounded-md border p-1.5 dark:border-gray-800">
                                            <p
                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                v-for="option in attribute.options"
                                            >
                                                @{{ option.name }}

                                                <span
                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                    @click="removeOption(option)"
                                                >
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    {{-- After Attributes Controls Event --}}
                                    {!! view_render_event('bagisto.admin.catalog.products.create_form.attributes.controls.after') !!}
                                </div>
                            </x-slot>

                            {{-- Modal Footer --}}
                            <x-slot:footer>
                                <div class="flex items-center gap-x-2.5">
                                    {{-- Back Button --}}
                                    <x-admin::button
                                        button-type="button"
                                        class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                                        :title="trans('admin::app.catalog.products.index.create.back-btn')"
                                        v-if="attributes.length"
                                        @click="attributes = []"
                                    />

                                    {{-- Save Button --}}
                                    <x-admin::button
                                        button-type="button"
                                        class="primary-button"
                                        :title="trans('admin::app.catalog.products.index.create.save-btn')"
                                        ::loading="isLoading"
                                        ::disabled="isLoading"
                                    />
                                </div>
                            </x-slot>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        {{-- Vue.js Component Script --}}
        <script type="module">
            app.component('v-create-product-form', {
                template: '#v-create-product-form-template',

                data() {
                    return {
                        attributes: [],
                        superAttributes: {},
                        isLoading: false,
                    };
                },

                methods: {
                    /**
                     * Create new product
                     * @param {Object} params - Form parameters
                     * @param {Object} formHelpers - Form helper functions
                     */
                    create(params, { resetForm, resetField, setErrors }) {
                        this.isLoading = true;

                        // Add super attributes to params
                        this.attributes.forEach(attribute => {
                            params.super_attributes ||= {};
                            params.super_attributes[attribute.code] = this.superAttributes[attribute.code];
                        });

                        // Submit form data
                        this.$axios.post("{{ route('admin.catalog.products.store') }}", params)
                            .then((response) => {
                                this.isLoading = false;

                                if (response.data.data.redirect_url) {
                                    // Redirect to product edit page
                                    window.location.href = response.data.data.redirect_url;
                                } else {
                                    // Show configurable attributes
                                    this.attributes = response.data.data.attributes;
                                    this.setSuperAttributes();
                                }
                            })
                            .catch(error => {
                                this.isLoading = false;

                                if (error.response.status == 422) {
                                    // Handle validation errors
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    /**
                     * Remove attribute option
                     * @param {Object} option - Option to remove
                     */
                    removeOption(option) {
                        this.attributes.forEach(attribute => {
                            attribute.options = attribute.options.filter(item => item.id != option.id);
                        });

                        // Remove attributes with no options
                        this.attributes = this.attributes.filter(attribute => attribute.options.length > 0);

                        this.setSuperAttributes();
                    },

                    /**
                     * Set super attributes for configurable products
                     */
                    setSuperAttributes() {
                        this.superAttributes = {};

                        this.attributes.forEach(attribute => {
                            this.superAttributes[attribute.code] = [];

                            attribute.options.forEach(option => {
                                this.superAttributes[attribute.code].push(option.id);
                            });
                        });
                    }
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>
