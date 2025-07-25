<?php

namespace Webkul\DeliveryAgents\Datagrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class StateDataGrid extends DataGrid
{
    protected $index = 'id';

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
            'type'       => 'string',
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

        ]);    }
    public function prepareActions()
    {

        if (bouncer()->hasPermission('delivery.country')){
            $this->addAction([
                'icon'   => 'icon-sort-left', //
                'title'  =>   'عرض',
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.states.view', $row->country_states_id);
                },
            ]);

        }

    }

}
