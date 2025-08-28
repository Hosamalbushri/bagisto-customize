<?php

namespace Webkul\DeliveryAgents\Datagrids\DeliveryAgent;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\DeliveryAgents\Models\State;

class DeliveryAgentDataGrid extends DataGrid
{
    protected $primaryColumn = 'delivery_agents_id';

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $tablePrefix = DB::getTablePrefix();
        $queryBuilder = DB::table('delivery_agents')
            ->leftJoin('delivery_agent_ranges', 'delivery_agents.id', '=', 'delivery_agent_ranges.delivery_agent_id')
            ->leftJoin('delivery_agent_orders', 'delivery_agents.id', '=', 'delivery_agent_orders.delivery_agent_id')

            ->addSelect(
                'delivery_agents.id as delivery_agents_id',
                'delivery_agents.email',
                'delivery_agents.phone',
                'delivery_agents.gender',
                'delivery_agents.status',
            )
            ->addSelect(DB::raw('COUNT(DISTINCT '.$tablePrefix.'delivery_agent_ranges.id) as range_count'))
            ->addSelect(DB::raw('COUNT(DISTINCT '.$tablePrefix.'delivery_agent_orders.id) as order_count'))
            ->addSelect(DB::raw('CONCAT('.$tablePrefix.'delivery_agents.first_name, " ", '.$tablePrefix.'delivery_agents.last_name) as full_name'))
            ->groupBy('delivery_agents_id');

        $this->addFilter('delivery_agents_id', 'delivery_agents.id');
        $this->addFilter('email', 'delivery_agents.email');
        $this->addFilter('full_name', DB::raw('CONCAT('.$tablePrefix.'delivery_agents.first_name, " ", '.$tablePrefix.'delivery_agents.last_name)'));
        $this->addFilter('phone', 'delivery_agents.phone');
        $this->addFilter('status', 'delivery_agents.status');

        return $queryBuilder;

    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function addColumns() {}

    /**
     * Prepare actions.
     *
     * @return void
     */
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

    public function prepareColumns()
    {
        $this->addColumn([
            'index'              => 'state',
            'label'              => trans('deliveryagent::app.deliveryagents.datagrid.state'),
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => State::query()
                ->orderBy('default_name')
                ->get()
                ->map(fn ($state) => [
                    'label' => $state->default_name,
                    'value' => $state->code,
                ])
                ->toArray(),
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
            'sortable'   => true,
            'closure'    => function ($row) {
                switch ($row->status) {
                    case '1':
                        return '<p class="label-active">'.trans('deliveryagent::app.deliveryagents.datagrid.active').'</p>';
                    case '0':
                        return '<p class="label-canceled">'.trans('deliveryagent::app.deliveryagents.datagrid.inactive').'</p>';
                }
            },

        ]);

        $this->addColumn([
            'index'      => 'delivery_agents_id',
            'label'      => trans('deliveryagent::app.deliveryagents.datagrid.id'),
            'type'       => 'integer',
            'filterable' => true,

        ]);
        $this->addColumn([
            'index'      => 'order_count',
            'label'      => trans('deliveryagent::app.deliveryagents.datagrid.order_count'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => false,
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
            'index'      => 'gender',
            'label'      => trans('deliveryagent::app.deliveryagents.datagrid.gender'),
            'type'       => 'string',
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'    => 'range_count',
            'label'    => trans('deliveryagent::app.deliveryagents.datagrid.range-count'),
            'type'     => 'integer',
            'sortable' => true,
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
