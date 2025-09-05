<?php

namespace Webkul\DeliveryAgents\Datagrids\DeliveryAgent\Views;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\DeliveryAgents\Models\Order;
use Webkul\Sales\Models\OrderAddress;

class OrderDateGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $tablePrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('orders')
            ->leftJoin('delivery_agent_orders', 'orders.id', '=', 'delivery_agent_orders.order_id')
            ->leftJoin('addresses as order_address_billing', function ($leftJoin) {
                $leftJoin->on('order_address_billing.order_id', '=', 'orders.id')
                    ->where('order_address_billing.address_type', OrderAddress::ADDRESS_TYPE_BILLING);
            })
            ->leftJoin('order_payment', 'orders.id', '=', 'order_payment.order_id')
            ->select(
                'orders.id',
                'orders.increment_id',
                'order_payment.method',
                'orders.base_grand_total',
                'orders.created_at',
                'channel_name',
                'order_address_billing.country as country_code',
                'order_address_billing.state as state_code',
                'delivery_agent_orders.status as status',
                'delivery_agent_orders.status as deliveryStatus',
                'order_address_billing.email as customer_email',
                DB::raw('CONCAT('.$tablePrefix.'order_address_billing.first_name, " ", '.$tablePrefix.'order_address_billing.last_name) as full_name'),
                DB::raw('CONCAT('.$tablePrefix.'order_address_billing.city, ", ", '.$tablePrefix.'order_address_billing.address) as location')
            )
            ->where('delivery_agent_orders.delivery_agent_id', request()->route('id'));

        $this->addFilter('full_name', DB::raw('CONCAT('.$tablePrefix.'orders.customer_first_name, " ", '.$tablePrefix.'orders.customer_last_name)'));
        $this->addFilter('created_at', 'orders.created_at');
        $this->addFilter('status', 'delivery_agent_orders.status');

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'increment_id',
            'label'      => trans('admin::app.customers.customers.view.datagrid.orders.order-id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'status',
            'label'              => trans('admin::app.customers.customers.view.datagrid.orders.status'),
            'type'               => 'string',
            'searchable'         => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('deliveryAgent::app.deliveryAgent.datagrid.orders.status.assigned_to_agent'),
                    'value' => Order::STATUS_ASSIGNED_TO_AGENT,
                ],
                [
                    'label' => trans('deliveryAgent::app.deliveryAgent.datagrid.orders.status.accepted_by_agent'),
                    'value' => Order::STATUS_ACCEPTED_BY_AGENT,
                ],
                [
                    'label' => trans('deliveryAgent::app.deliveryAgent.datagrid.orders.status.rejected_by_agent'),
                    'value' => Order::STATUS_REJECTED_BY_AGENT,
                ],
                [
                    'label' => trans('deliveryAgent::app.deliveryAgent.datagrid.orders.status.out_for_delivery'),
                    'value' => Order::STATUS_OUT_FOR_DELIVERY,
                ],
                [
                    'label' => trans('deliveryAgent::app.deliveryAgent.datagrid.orders.status.delivered'),
                    'value' => Order::STATUS_DELIVERED,
                ],
                [
                    'label' => trans('admin::app.sales.orders.index.datagrid.canceled'),
                    'value' => Order::STATUS_CANCELED,
                ],
                [
                    'label' => trans('admin::app.sales.orders.index.datagrid.closed'),
                    'value' => Order::STATUS_CLOSED,
                ],
            ],
            'sortable'   => true,
            'closure'    => function ($row) {
                switch ($row->status) {
                    case Order::STATUS_ASSIGNED_TO_AGENT:
                        return '<p class="label-assigned_to_agent">'.trans('deliveryAgent::app.deliveryAgent.orders.status.assigned_to_agent').'</p>';

                    case Order::STATUS_ACCEPTED_BY_AGENT:
                        return '<p class="label-closed">'.trans('deliveryAgent::app.deliveryAgent.orders.status.accepted_by_agent').'</p>';

                    case Order::STATUS_REJECTED_BY_AGENT:
                        return '<p class="label-rejected_by_agent">'.trans('deliveryAgent::app.deliveryAgent.orders.status.rejected_by_agent').'</p>';

                    case Order::STATUS_OUT_FOR_DELIVERY:
                        return '<p class="label-out_for_delivery">'.trans('deliveryAgent::app.deliveryAgent.orders.status.out_for_delivery').'</p>';

                    case Order::STATUS_DELIVERED:
                        return '<p class="label-delivered">'.trans('deliveryAgent::app.deliveryAgent.orders.status.delivered').'</p>';

                    case Order::STATUS_CANCELED:
                        return '<p class="label-canceled">'.trans('admin::app.sales.orders.index.datagrid.canceled').'</p>';

                    case Order::STATUS_CLOSED:
                        return '<p class="label-closed">'.trans('admin::app.sales.orders.index.datagrid.closed').'</p>';
                }

            },
        ]);

        $this->addColumn([
            'index'      => 'base_grand_total',
            'label'      => trans('admin::app.customers.customers.view.datagrid.orders.grand-total'),
            'type'       => 'string',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'method',
            'label'      => trans('admin::app.customers.customers.view.datagrid.orders.pay-via'),
            'type'       => 'string',
            'closure'    => function ($row) {
                return core()->getConfigData('sales.payment_methods.'.$row->method.'.title');
            },
        ]);

        $this->addColumn([
            'index'              => 'deliveryStatus',
            'label'              => trans('admin::app.customers.customers.view.datagrid.orders.channel-name'),
            'type'               => 'string',

        ]);

        $this->addColumn([
            'index'      => 'full_name',
            'label'      => trans('admin::app.customers.customers.view.datagrid.orders.customer-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
        ]);

        /**
         * Searchable dropdown sample. In testing phase.
         */
        $this->addColumn([
            'index'      => 'customer_email',
            'label'      => trans('admin::app.customers.customers.view.datagrid.orders.email'),
            'type'       => 'string',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'location',
            'label'      => trans('admin::app.customers.customers.view.datagrid.orders.location'),
            'type'       => 'string',
        ]);

        $this->addColumn([
            'index'      => 'country_code',
            'label'      => trans('admin::app.customers.customers.view.datagrid.orders.location'),
            'type'       => 'string',
            'closure'    => function ($row) {
                return core()->country_name($row->country_code);
            },
        ]);
        $this->addColumn([
            'index'      => 'state_code',
            'label'      => trans('admin::app.customers.customers.view.datagrid.orders.location'),
            'type'       => 'string',
            'closure'    => function ($row) {
                return myHelper()->state_name($row->state_code);
            },
        ]);
        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('admin::app.sales.orders.index.datagrid.date'),
            'type'            => 'date',
            'filterable'      => true,
            'filterable_type' => 'date_range',
            'sortable'        => true,
            'closure'         => function ($row) {
                return myHelper()->formatDate($row->created_at);
            },
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('sales.orders.view')) {
            $this->addAction([
                'icon'   => 'icon-view',
                'title'  => trans('admin::app.customers.customers.view.datagrid.orders.view'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.orders.view', $row->id);
                },
            ]);
        }
    }
}
