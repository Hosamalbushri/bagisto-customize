<x-admin::layouts>
    <x-slot:title>
        @lang('adminTheme::app.country.index.title')
    </x-slot>
    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('adminTheme::app.country.index.title')
        </p>
        <div class="flex items-center gap-x-2.5">

            <div class="flex items-center gap-x-2.5">
                @if (bouncer()->hasPermission('delivery.countries.country.create'))
                    @include('adminTheme::admin.Countries.index.create')
                    <v-create-country-form
                        ref="CreateCountryComponent"
                        @country-created="$refs.CountryDatagrid.get()"
                    ></v-create-country-form>
                    <button
                        class="primary-button"
                        @click="$refs.CreateCountryComponent.openModal()"

                    >
                        @lang('adminTheme::app.country.create.index-create-btn')
                    </button>
                @endif
            </div>
        </div>
    </div>


    <x-admin::datagrid
        src="{{ route('admin.country.index') }}"
        ref="CountryDatagrid"
    >
    </x-admin::datagrid>
</x-admin::layouts>

