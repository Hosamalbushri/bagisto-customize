<?php

namespace Webkul\DeliveryAgents\Datagrids\Country\Areas\View;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class DeliveryAgentDataGrid extends DataGrid
{
    protected $primaryColumn = 'delivery_agents_id';

    protected $mode = null;

    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $tablePrefix = DB::getTablePrefix();
        $areaId = request()->route('id');
        $this->mode = $this->mode ?? request()->get('mode', 'in');
        $queryBuilder = DB::table('delivery_agents')
            ->leftJoin('delivery_agent_ranges', 'delivery_agents.id', '=', 'delivery_agent_ranges.delivery_agent_id')
            ->leftJoin('delivery_agent_orders', 'delivery_agents.id', '=', 'delivery_agent_orders.delivery_agent_id')

            ->addSelect(
                'delivery_agents.id as delivery_agents_id',
                'delivery_agents.email',
                'delivery_agents.phone',
                'delivery_agents.gender',
                'delivery_agents.status',
                'delivery_agent_ranges.state_area_id',
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
        if ($this->mode === 'in') {
            $queryBuilder->whereIn('delivery_agents.id', function ($q) use ($areaId) {
                $q->select('delivery_agent_id')
                    ->from('delivery_agent_ranges')
                    ->where('state_area_id', $areaId);
            });
        } else { // out
            $queryBuilder->whereNotIn('delivery_agents.id', function ($q) use ($areaId) {
                $q->select('delivery_agent_id')
                    ->from('delivery_agent_ranges')
                    ->where('state_area_id', $areaId);
            });
        }

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
            'index'      => 'order_count',
            'label'      => trans('deliveryAgent::app.deliveryAgent.dataGrid.order_count'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => false,
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

    }

    public function prepareMassActions()
    {
        if (bouncer()->hasPermission('delivery.deliveryAgent.range.delete')) {
            if ($this->mode === 'in') {
                $this->addMassAction([
                    'title'  => trans('deliveryAgent::app.country.state.area.view.dataGrid.delete-from-area'),
                    'method' => 'POST',
                    'url'    => route('admin.range.mass_delete', request()->route('id')),
                ]);
            }

        }
        if (bouncer()->hasPermission('delivery.deliveryAgent.range.create')) {
            if ($this->mode === 'out') {

                $this->addMassAction([
                    'title'  => trans('deliveryAgent::app.country.state.area.view.dataGrid.add-to-area'),
                    'method' => 'POST',
                    'url'    => route('admin.range.mass_add', request()->route('id')),
                ]);
            }
        }
    }
}
