<?php

namespace Webkul\DeliveryAgents\Datagrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class StateDataGrid extends DataGrid
{
    protected $primaryColumn = 'country_states_id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('country_states')
            ->addSelect(
                'country_states.id as country_states_id',
                'country_states.default_name',
                'country_states.code',
                'country_states.country_id',
            )
            ->where('country_id', request('country_id'));
        $this->addFilter('id', 'country_states.id');
        $this->addFilter('code', 'country_states.code');
        $this->addFilter('default_name', 'country_states.default_name');

        return $queryBuilder;

    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'country_states_id',
            'label'      => trans('deliveryagent::app.country.state.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,

        ]);

        $this->addColumn([
            'index'      => 'default_name',
            'label'      => trans('deliveryagent::app.country.state.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,

        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('deliveryagent::app.country.state.datagrid.code'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,

        ]);
    }

    public function prepareActions()
    {

        if (bouncer()->hasPermission('countries.states.edit')) {
            $this->addAction([
                'icon'   => 'icon-sort-left',
                'title'  => trans('deliveryagent::app.country.state.datagrid.actions.view'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.states.edit', $row->country_states_id);
                },
            ]);

        }
        if (bouncer()->hasPermission('countries.states.delete')) {

            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('deliveryagent::app.country.state.datagrid.actions.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.states.delete', $row->country_states_id);
                },
            ]);
        }

    }
    public function prepareMassActions()
    {
        if (bouncer()->hasPermission('countries.states.delete')) {
            $this->addMassAction([
                'title'  => trans('deliveryagent::app.country.state.datagrid.actions.delete'),
                'method' => 'POST',
                'url'    => route('admin.states.mass_delete'),
            ]);
        }
    }
}
