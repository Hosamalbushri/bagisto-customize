<div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
    <div class="flex justify-between">
        <!-- Total Order Count -->
        <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
        {{ __('deliveryagent::app.country.view.states.count', ['count' => '']) }}(@{{ statesCount }})
        </p>

    </div>

    <x-admin::datagrid
        src="{{ route('admin.states.index', ['country_id' => $country->id]) }}"
        ref="StatesDatagrid"
        :isMultiRow="true"
    />
</div>
