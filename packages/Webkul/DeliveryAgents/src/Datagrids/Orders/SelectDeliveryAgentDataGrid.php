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
            'label'      => trans('deliveryagent::app.deliveryagents.datagrid.id'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => false,

        ]);

        $this->addColumn([
            'index'      => 'full_name',
            'label'      => trans('deliveryagent::app.deliveryagents.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);


        $this->addColumn([
            'index'      => 'email',
            'label'      => trans('deliveryagent::app.deliveryagents.datagrid.email'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'phone',
            'label'      => trans('deliveryagent::app.deliveryagents.datagrid.phone'),
            'type'       => 'string',
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'              => 'status',
            'label'              => trans('deliveryagent::app.deliveryagents.datagrid.status'),
            'type'               => 'boolean',
            'filterable'         => true,
            'filterable_options' => [
                [
                    'label' => trans('deliveryagent::app.deliveryagents.datagrid.active'),
                    'value' => 1,
                ],
                [
                    'label' => trans('deliveryagent::app.deliveryagents.datagrid.inactive'),
                    'value' => 0,
                ],
            ],
            'sortable' => true,

        ]);
    }

    public function prepareActions()
    {

        $this->addAction([
            'icon'   => 'icon-sort-left',
            'title'  => trans('deliveryagent::app.deliveryagents.datagrid.actions.view'),
            'method' => 'GET',
            'target' => 'blank',
            'url'    => function ($row) {
                return route('admin.deliveryagents.view', $row->delivery_agents_id);
            },
        ]);

    }
    public function prepareMassActions()
    {
        if (bouncer()->hasPermission('delivery.deliveryAgent.delete')) {
            $this->addMassAction([
                'title'  => trans('deliveryagent::app.deliveryagents.datagrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.deliveryagents.mass_delete'),
            ]);
        }

        if (bouncer()->hasPermission('delivery.deliveryAgent.edit')) {
            $this->addMassAction([
                'title'   => trans('deliveryagent::app.deliveryagents.datagrid.update-status'),
                'method'  => 'POST',
                'url'     => route('admin.deliveryagents.mass_update'),
                'options' => [
                    [
                        'label' => trans('deliveryagent::app.deliveryagents.datagrid.active'),
                        'value' => 1,
                    ],
                    [
                        'label' => trans('deliveryagent::app.deliveryagents.datagrid.inactive'),
                        'value' => 0,
                    ],
                ],
            ]);
        }
    }

}
