<?php

namespace Webkul\DeliveryAgents\Datagrids\Country;

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
            'label'      => trans('deliveryAgent::app.country.state.dataGrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,

        ]);

        $this->addColumn([
            'index'      => 'default_name',
            'label'      => trans('deliveryAgent::app.country.state.dataGrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,

        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('deliveryAgent::app.country.state.dataGrid.code'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,

        ]);
    }

    public function prepareActions()
    {

        $this->addAction([
            'icon'   => 'icon-sort-left',
            'title'  => trans('deliveryAgent::app.country.state.dataGrid.actions.view'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.states.edit', $row->country_states_id);
            },
        ]);

        if (bouncer()->hasPermission('delivery.countries.states.delete')) {

            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('deliveryAgent::app.country.state.dataGrid.actions.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.states.delete', $row->country_states_id);
                },
            ]);
        }

    }

    public function prepareMassActions()
    {
        if (bouncer()->hasPermission('delivery.countries.states.delete')) {
            $this->addMassAction([
                'title'  => trans('deliveryAgent::app.country.state.dataGrid.actions.delete'),
                'method' => 'POST',
                'url'    => route('admin.states.mass_delete'),
            ]);
        }
    }
}
