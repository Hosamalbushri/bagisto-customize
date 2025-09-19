<?php

namespace Webkul\DeliveryAgents\Datagrids\DeliveryAgent;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

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
            ->leftJoin('delivery_agent_reviews', 'delivery_agents.id', '=', 'delivery_agent_reviews.delivery_agent_id')
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
            ->addSelect(DB::raw('COALESCE(LEAST(5, GREATEST(1, ROUND(AVG(CASE WHEN '.$tablePrefix.'delivery_agent_reviews.status = "approved" THEN '.$tablePrefix.'delivery_agent_reviews.rating END), 1))),0) as average_rating'))
            ->addSelect(DB::raw("COUNT(DISTINCT CASE WHEN {$tablePrefix}delivery_agent_orders.status IN ('assigned_to_agent', 'accepted_by_agent', 'out_for_delivery') THEN {$tablePrefix}delivery_agent_orders.id END) as current_orders_count"))
            ->groupBy('delivery_agents.id', 'delivery_agents.email', 'delivery_agents.phone', 'delivery_agents.gender', 'delivery_agents.status', 'delivery_agents.first_name', 'delivery_agents.last_name');
        $this->addFilter('delivery_agents_id', 'delivery_agents.id');
        $this->addFilter('email', 'delivery_agents.email');
        $this->addFilter('full_name', DB::raw('CONCAT('.$tablePrefix.'delivery_agents.first_name, " ", '.$tablePrefix.'delivery_agents.last_name)'));
        $this->addFilter('phone', 'delivery_agents.phone');
        $this->addFilter('status', 'delivery_agents.status');
        $this->addFilter('average_rating', 'delivery_agent_reviews.rating');


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
            'title'  => trans('deliveryAgent::app.deliveryAgent.dataGrid.actions.view'),
            'method' => 'GET',
            'target' => 'blank',
            'url'    => function ($row) {
                return route('admin.deliveryAgents.view', $row->delivery_agents_id);
            },
        ]);

    }

    public function prepareColumns()
    {
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

        $this->addColumn([
            'index'      => 'delivery_agents_id',
            'label'      => trans('deliveryAgent::app.deliveryAgent.dataGrid.id'),
            'type'       => 'integer',
            'filterable' => true,

        ]);
        $this->addColumn([
            'index'      => 'current_orders_count',
            'label'      => trans('deliveryAgent::app.deliveryAgent.dataGrid.order_count'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
            'closure'    => function ($row) {
                if ($row->current_orders_count > 0) {
                    return '<p class="text-gray-600 dark:text-gray-300">'
                        .trans('deliveryAgent::app.deliveryAgent.dataGrid.order', ['order' => $row->current_orders_count])
                        .'</p>';
                } else {
                    return '<p class="text-gray-600 dark:text-gray-300">'
                        .trans('deliveryAgent::app.deliveryAgent.dataGrid.no-order')
                        .'</p>';
                }

            },

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
            'index'      => 'gender',
            'label'      => trans('deliveryAgent::app.deliveryAgent.dataGrid.gender'),
            'type'       => 'string',
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'    => 'range_count',
            'label'    => trans('deliveryAgent::app.deliveryAgent.dataGrid.range-count'),
            'type'     => 'integer',
            'sortable' => true,
        ]);
        $this->addColumn([
            'index'              => 'average_rating',
            'label'              => trans('deliveryAgent::app.deliveryAgent.dataGrid.rating'),
            'type'               => 'string',
            'searchable'         => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => array_map(function ($value) {
                return [
                    'label' => $value,
                    'value' => (string) $value,
                ];
            }, range(1, 5)),
            'sortable'   => true,

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
