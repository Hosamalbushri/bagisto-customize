<?php

namespace Webkul\DeliveryAgents\Datagrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CountryDataGrid extends DataGrid
{
    protected $primaryColumn = 'countries_id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('countries')
            ->leftJoin('country_states', 'countries.id', '=', 'country_states.country_id')
            ->addSelect(
                'countries.id as countries_id',
                'countries.code',
                'countries.name',
                DB::raw('COUNT(country_states.id) as states_count')
            )
            ->groupBy('countries.id', 'countries.code', 'countries.name');

        $this->addFilter('id', 'countries.id');
        $this->addFilter('code', 'countries.code');
        $this->addFilter('name', 'countries.name');

        return $queryBuilder;
    }

    public function prepareColumns()
    {

        $this->addColumn([
            'index'      => 'countries_id',
            'label'      => trans('deliveryagent::app.country.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,

        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('deliveryagent::app.country.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,

        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('deliveryagent::app.country.datagrid.code'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,

        ]);
        $this->addColumn([
            'index'      => 'states_count',
            'label'      => trans('deliveryagent::app.country.datagrid.states_count'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
        ]);
    }

    public function prepareActions()
    {
        if (bouncer()->hasPermission('delivery.countries.country.edit')) {
            $this->addAction([
                'icon'   => 'icon-sort-left',
                'title'  => trans('deliveryagent::app.country.datagrid.actions.view'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.country.edit', $row->countries_id);
                },
            ]);

        }
        if (bouncer()->hasPermission('delivery.countries.country.delete')) {

            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('deliveryagent::app.country.datagrid.actions.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.country.delete', $row->countries_id);
                },
            ]);
        }

    }

    public function prepareMassActions()
    {
        if (bouncer()->hasPermission('delivery.countries.country.delete')) {
            $this->addMassAction([
                'title'  => trans('deliveryagent::app.country.datagrid.actions.delete'),
                'method' => 'POST',
                'url'    => route('admin.country.mass_delete'),
            ]);
        }
    }
}
