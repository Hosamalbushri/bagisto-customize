<?php

namespace Webkul\DeliveryAgents\Datagrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\DeliveryAgents\Models\State;

class DeliveryAgentDataGrid extends DataGrid
{
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
            ->select(
                'delivery_agents.id as delivery_agents_id',
                'delivery_agents.email',
                'delivery_agents.phone',
                'delivery_agents.gender',
                'delivery_agents.status',
                'delivery_agent_ranges.state',
                'delivery_agent_ranges.country'
            )
            ->addSelect(DB::raw('COUNT(DISTINCT '.$tablePrefix.'delivery_agent_ranges.id) as range_count'))
            ->addSelect(DB::raw('CONCAT('.$tablePrefix.'delivery_agents.first_name, " ", '.$tablePrefix.'delivery_agents.last_name) as full_name'))
            ->groupBy('delivery_agents_id')
            ->orderBy('delivery_agents_id')
            ->orderBy('delivery_agent_ranges.id');

        $this->addFilter('delivery_agents_id', 'delivery_agents.id');
        $this->addFilter('email', 'delivery_agents.email');
        $this->addFilter('full_name', DB::raw('CONCAT('.$tablePrefix.'delivery_agents.first_name, " ", '.$tablePrefix.'delivery_agents.last_name)'));
        $this->addFilter('phone', 'delivery_agents.phone');
        $this->addFilter('status', 'delivery_agents.status');
        $this->addFilter('state', 'delivery_agent_ranges.state'); // تصحيح من states إلى state

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

        if (bouncer()->hasPermission('delivery.deliveryAgent.view-details')) {
            $this->addAction([
                'icon'   => 'icon-sort-left',
                'title'  => 'عرض',
                'method' => 'GET',
                'target' => 'blank',
                'url'    => function ($row) {
                    return route('admin.deliveryagents.view', $row->delivery_agents_id);
                },
            ]);

        }

    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'              => 'state',
            'label'              => trans('deliveryagent::app.deliveryagents.datagrid.state'), // تصحيح الترجمة من country إلى state
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => State::query()
                ->orderBy('default_name')
                ->get()
                ->map(fn ($state) => [ // تغيير اسم المتغير من $country إلى $state ليعكس المحتوى الحقيقي
                    'label' => $state->default_name,
                    'value' => $state->code, // أو $state->id حسب ما تستخدمه للتصفية
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
            'sortable' => true,

        ]);

        $this->addColumn([
            'index'      => 'delivery_agents_id',
            'label'      => trans('deliveryagent::app.deliveryagents.datagrid.id'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,

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
        //         if (bouncer()->hasPermission('delivery.deliveryAgent.delete')) {
        //             $this->addMassAction([
        //                 'title'  => trans('admin::app.customers.customers.index.datagrid.delete'),
        //                 'method' => 'POST',
        //                 'url'    => route('admin.customers.customers.mass_delete'),
        //             ]);
        //         }
        //
        //         if (bouncer()->hasPermission('customers.customers.edit')) {
        //             $this->addMassAction([
        //                 'title'   => trans('admin::app.customers.customers.index.datagrid.update-status'),
        //                 'method'  => 'POST',
        //                 'url'     => route('admin.customers.customers.mass_update'),
        //                 'options' => [
        //                     [
        //                         'label' => trans('admin::app.customers.customers.index.datagrid.active'),
        //                         'value' => 1,
        //                     ],
        //                     [
        //                         'label' => trans('admin::app.customers.customers.index.datagrid.inactive'),
        //                         'value' => 0,
        //                     ],
        //                 ],
        //             ]);
        //         }
    }
}
