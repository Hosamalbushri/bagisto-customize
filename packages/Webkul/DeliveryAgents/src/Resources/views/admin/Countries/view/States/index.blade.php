
    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
    <div class="flex justify-between">
        @if (bouncer()->hasPermission('delivery.countries.states.create'))
            @include('deliveryagents::admin.Countries.view.States.create')
        @endif
    </div>
    <x-admin::datagrid
        src="{{ route('admin.states.index', ['country_id' => $country->id]) }}"
        ref="StatesDatagrid"

    >

    </x-admin::datagrid>

    </div>
