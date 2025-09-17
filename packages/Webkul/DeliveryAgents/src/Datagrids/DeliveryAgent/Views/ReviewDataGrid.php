<?php

namespace Webkul\DeliveryAgents\Datagrids\DeliveryAgent\Views;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\DeliveryAgents\Models\DeliveryAgentReview;

class ReviewDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('delivery_agent_reviews')
            ->leftJoin('delivery_agents', 'delivery_agent_reviews.delivery_agent_id', '=', 'delivery_agents.id')
            ->leftJoin('orders', 'delivery_agent_reviews.order_id', '=', 'orders.id')
            ->leftJoin('customers', 'delivery_agent_reviews.customer_id', '=', 'customers.id')
            ->addSelect(
                'delivery_agent_reviews.id',
                'delivery_agent_reviews.rating',
                'delivery_agent_reviews.comment',
                'delivery_agent_reviews.status',
                'delivery_agent_reviews.created_at',
                'delivery_agent_reviews.updated_at',
                'delivery_agents.first_name as agent_first_name',
                'delivery_agents.last_name as agent_last_name',
                'orders.increment_id as order_increment_id',
                'customers.first_name as customer_first_name',
                'customers.last_name as customer_last_name'
            )->where('delivery_agent_reviews.delivery_agent_id', request()->route('id'));

        return $queryBuilder;
    }

    /**
     * Add columns.
     *     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('deliveryAgent::app.deliveryAgent.review.id'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'order_increment_id',
            'label'      => trans('deliveryAgent::app.deliveryAgent.review.order_id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                return '#'.$row->order_increment_id;
            },
        ]);

        $this->addColumn([
            'index'      => 'agent_name',
            'label'      => trans('deliveryAgent::app.deliveryAgent.review.delivery_agent'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => false,
            'closure'    => function ($row) {
                return $row->agent_first_name.' '.$row->agent_last_name;
            },
        ]);

        $this->addColumn([
            'index'      => 'customer_name',
            'label'      => trans('deliveryAgent::app.deliveryAgent.review.customer'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => false,
            'closure'    => function ($row) {
                return $row->customer_first_name.' '.$row->customer_last_name;
            },
        ]);

        $this->addColumn([
            'index'              => 'rating',
            'label'              => trans('deliveryAgent::app.deliveryAgent.review.rating'),
            'type'               => 'string',
            'searchable'         => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => array_map(function ($value) {
                return [
                    'label'  => $value,
                    'value'  => (string) $value,
                ];
            },
                range(1, 5)),
            'sortable'           => true,
        ]);

        $this->addColumn([
            'index'      => 'comment',
            'label'      => trans('deliveryAgent::app.deliveryAgent.review.comment'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => false,
            'sortable'   => false,
            'closure'    => function ($row) {
                return $row->comment ? (strlen($row->comment) > 50 ? substr($row->comment, 0, 50).'...' : $row->comment) : '-';
            },
        ]);

        $this->addColumn([
            'index'              => 'status',
            'label'              => trans('deliveryAgent::app.deliveryAgent.review.status.status'),
            'type'               => 'string',
            'searchable'         => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label'  => trans('deliveryAgent::app.deliveryAgent.review.status.pending'),
                    'value'  => DeliveryAgentReview::STATUS_PENDING,
                ],
                [
                    'label'  => trans('deliveryAgent::app.deliveryAgent.review.status.approved'),
                    'value'  => DeliveryAgentReview::STATUS_APPROVED,
                ],
                [
                    'label'  => trans('deliveryAgent::app.deliveryAgent.review.status.disapproved'),
                    'value'  => DeliveryAgentReview::STATUS_DISAPPROVED,
                ],
            ],
            'sortable'   => true,
            'closure'    => function ($row) {
                switch ($row->status) {
                    case DeliveryAgentReview::STATUS_PENDING:
                        return '<p class="label-pending">'.trans('deliveryAgent::app.deliveryAgent.review.status.pending').'</p>';

                    case DeliveryAgentReview::STATUS_APPROVED:
                        return '<p class="label-active">'.trans('deliveryAgent::app.deliveryAgent.review.status.approved').'</p>';

                    case DeliveryAgentReview::STATUS_DISAPPROVED:
                        return '<p class="label-canceled">'.trans('deliveryAgent::app.deliveryAgent.review.status.disapproved').'</p>';

                    default:
                        return '<p class="label-pending">'.$row->status.'</p>';
                }
            },
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('deliveryAgent::app.deliveryAgent.review.created_at'),
            'type'            => 'date',
            'searchable'      => true,
            'filterable'      => true,
            'filterable_type' => 'date_range',
            'sortable'        => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        //        $this->addAction([
        //            'icon'   => 'icon-eye',
        //            'title'  => trans('deliveryAgent::app.deliveryAgent.review.actions.view'),
        //            'method' => 'GET',
        //            'url'    => function ($row) {
        // //                return route('admin.delivery_agents.reviews.view', $row->id);
        //            },
        //        ]);
        //
        //        $this->addAction([
        //            'icon'   => 'icon-edit',
        //            'title'  => trans('deliveryAgent::app.datagrid.actions.edit'),
        //            'method' => 'GET',
        //            'url'    => function ($row) {
        //                return route('admin.delivery_agents.reviews.edit', $row->id);
        //            },
        //        ]);
        //
        //        $this->addAction([
        //            'icon'   => 'icon-trash',
        //            'title'  => trans('deliveryAgent::app.datagrid.actions.delete'),
        //            'method' => 'DELETE',
        //            'url'    => function ($row) {
        //                return route('admin.delivery_agents.reviews.delete', $row->id);
        //            },
        //        ]);
    }
}
