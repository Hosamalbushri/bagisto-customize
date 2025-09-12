<?php

namespace Webkul\DeliveryAgents\Datagrids\Orders;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class SelectDeliveryAgentDataGrid extends DataGrid
{
    protected $primaryColumn = 'delivery_agents_id';

    public function prepareQueryBuilder()
    {
        $areaId = request()->get('area_id');
        $tablePrefix = DB::getTablePrefix();
        $queryBuilder = DB::table('delivery_agents')
            ->leftJoin('delivery_agent_ranges', 'delivery_agents.id', '=', 'delivery_agent_ranges.delivery_agent_id')
            ->addSelect(
                'delivery_agents.id as delivery_agents_id',
                'delivery_agents.email',
                'delivery_agents.phone',
                'delivery_agents.gender',
                'delivery_agent_ranges.state_area_id',
                'delivery_agents.status',
            )
            ->addSelect(DB::raw('CONCAT('.$tablePrefix.'delivery_agents.first_name, " ", '.$tablePrefix.'delivery_agents.last_name) as full_name'))
            ->groupBy('delivery_agents_id');
        $this->addFilter('full_name', DB::raw('CONCAT('.$tablePrefix.'delivery_agents.first_name, " ", '.$tablePrefix.'delivery_agents.last_name)'));
        $this->addFilter('phone', 'delivery_agents.phone');
        $this->addFilter('status', 'delivery_agents.status');
        if ($areaId) {
            $queryBuilder->where('delivery_agent_ranges.state_area_id', $areaId);
        }

        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'delivery_agents_id',
            'label'      => trans('deliveryAgent::app.deliveryAgent.dataGrid.id'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => false,

        ]);

        $this->addColumn([
            'index'      => 'full_name',
            'label'      => trans('deliveryAgent::app.deliveryAgent.dataGrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'email',
            'label'      => trans('deliveryAgent::app.deliveryAgent.dataGrid.email'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'phone',
            'label'      => trans('deliveryAgent::app.deliveryAgent.dataGrid.phone'),
            'type'       => 'string',
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'              => 'status',
            'label'              => trans('deliveryAgent::app.deliveryAgent.dataGrid.status'),
            'type'               => 'boolean',
            'filterable'         => true,
            'filterable_options' => [
                [
                    'label' => trans('deliveryAgent::app.deliveryAgent.dataGrid.active'),
                    'value' => 1,
                ],
                [
                    'label' => trans('deliveryAgent::app.deliveryAgent.dataGrid.inactive'),
                    'value' => 0,
                ],
            ],
            'sortable'   => true,
            'closure'    => function ($row) {
                switch ($row->status) {
                    case '1':
                        return '<p class="label-active">'.trans('deliveryAgent::app.deliveryAgent.dataGrid.active').'</p>';
                    case '0':
                        return '<p class="label-canceled">'.trans('deliveryAgent::app.deliveryAgent.dataGrid.inactive').'</p>';
                }
            },

        ]);
    }

    public function prepareActions()
    {

        $this->addAction([
            'icon'   => 'icon-sort-left',
            'title'  => trans('deliveryAgent::app.deliveryAgent.dataGrid.actions.view'),
            'method' => 'GET',
            'target' => 'blank',
            'url'    => function ($row) {
                return route('admin.deliveryAgents.view', $row->delivery_agents_id);
            },
        ]);

    }

    public function prepareMassActions()
    {
        if (bouncer()->hasPermission('delivery.deliveryAgent.delete')) {
            $this->addMassAction([
                'title'  => trans('deliveryAgent::app.deliveryAgent.dataGrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.deliveryAgents.mass_delete'),
            ]);
        }

        if (bouncer()->hasPermission('delivery.deliveryAgent.edit')) {
            $this->addMassAction([
                'title'   => trans('deliveryAgent::app.deliveryAgent.dataGrid.update-status'),
                'method'  => 'POST',
                'url'     => route('admin.deliveryAgents.mass_update'),
                'options' => [
                    [
                        'label' => trans('deliveryAgent::app.deliveryAgent.dataGrid.active'),
                        'value' => 1,
                    ],
                    [
                        'label' => trans('deliveryAgent::app.deliveryAgent.dataGrid.inactive'),
                        'value' => 0,
                    ],
                ],
            ]);
        }
    }
}
