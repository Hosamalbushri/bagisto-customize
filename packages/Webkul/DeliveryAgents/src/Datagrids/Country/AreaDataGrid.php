<?php

namespace Webkul\DeliveryAgents\Datagrids\Country;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class AreaDataGrid extends DataGrid
{
    protected $primaryColumn = 'state_areas_id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('state_areas')
            ->leftJoin('delivery_agent_ranges', 'state_areas.id', '=', 'delivery_agent_ranges.state_area_id')

            ->addSelect(
                'state_areas.id as state_areas_id',
                'state_areas.area_name',
                'state_areas.country_code',
                DB::raw('COUNT(delivery_agent_ranges.delivery_agent_id) as delivery_agents_count')

            )
            ->where('country_state_id', request('country_state_id'))
            ->groupBy('state_areas.id', 'state_areas.area_name');

        return $queryBuilder;

    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'state_areas_id',
            'label'      => trans('deliveryAgent::app.country.state.area.dataGrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,

        ]);
        $this->addColumn([
            'index'      => 'area_name',
            'label'      => trans('deliveryAgent::app.country.state.area.dataGrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,

        ]);
        $this->addColumn([
            'index'      => 'delivery_agents_count',
            'label'      => trans('deliveryAgent::app.country.state.area.dataGrid.delivery-count'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,

        ]);
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('delivery.countries.states.area.edit')) {
            $this->addAction([
                'index'  => 'edit',
                'icon'   => 'icon-edit',
                'title'  => trans('deliveryAgent::app.country.state.area.dataGrid.actions.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.area.edit', $row->state_areas_id);

                },
            ]);

        }
        if (bouncer()->hasPermission('delivery.countries.states.area.delete')) {

            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('deliveryAgent::app.country.state.area.dataGrid.actions.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.area.delete', $row->state_areas_id);
                },
            ]);
        }

    }
}
