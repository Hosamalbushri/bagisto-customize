<div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
    <div class="flex justify-between">

        <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
            @include('deliveryagents::admin.Countries.view.States.create')

        </p>


    </div>


    <x-admin::datagrid
        src="{{ route('admin.states.index', ['country_id' => $country->id]) }}"
        ref="StatesDatagrid"
        :isMultiRow="true"
    />
</div>
