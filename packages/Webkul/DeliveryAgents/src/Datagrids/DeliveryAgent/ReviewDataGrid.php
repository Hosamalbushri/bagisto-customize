<?php

namespace Webkul\DeliveryAgents\Datagrids\DeliveryAgent;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ReviewDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @var string
     */
    protected $primaryColumn = 'id';

    /**
     * Review status "approved".
     */
    const STATUS_APPROVED = 'approved';

    /**
     * Review status "pending", indicating awaiting approval or processing.
     */
    const STATUS_PENDING = 'pending';

    /**
     * Review status "disapproved", indicating rejection or denial.
     */
    const STATUS_DISAPPROVED = 'disapproved';

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
            ->select(
                'delivery_agent_reviews.id',
                'delivery_agent_reviews.rating',
                'delivery_agent_reviews.comment',
                'delivery_agent_reviews.status',
                'delivery_agent_reviews.created_at',
                'delivery_agent_reviews.order_id',
                'delivery_agents.first_name as agent_first_name',
                'delivery_agents.last_name as agent_last_name',
                'orders.increment_id as order_increment_id',
                'customers.first_name as customer_first_name',
                'customers.last_name as customer_last_name'
            );

        $this->addFilter('id', 'delivery_agent_reviews.id');
        $this->addFilter('status', 'delivery_agent_reviews.status');
        $this->addFilter('rating', 'delivery_agent_reviews.rating');
        $this->addFilter('created_at', 'delivery_agent_reviews.created_at');

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
            'index'      => 'customer_name',
            'label'      => trans('deliveryAgent::app.deliveryAgent.review.customer'),
            'type'       => 'string',
            'sortable'   => true,
            'closure'    => function ($row) {
                return $row->customer_first_name.' '.$row->customer_last_name;
            },
        ]);

        $this->addColumn([
            'index'      => 'agent_name',
            'label'      => trans('deliveryAgent::app.deliveryAgent.review.delivery_agent'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                return $row->agent_first_name.' '.$row->agent_last_name;
            },
        ]);

        $this->addColumn([
            'index'              => 'status',
            'label'              => trans('deliveryAgent::app.deliveryAgent.review.status.status'),
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('deliveryAgent::app.deliveryAgent.review.status.approved'),
                    'value' => self::STATUS_APPROVED,
                ],
                [
                    'label' => trans('deliveryAgent::app.deliveryAgent.review.status.pending'),
                    'value' => self::STATUS_PENDING,
                ],
                [
                    'label' => trans('deliveryAgent::app.deliveryAgent.review.status.disapproved'),
                    'value' => self::STATUS_DISAPPROVED,
                ],
            ],
            'sortable'   => true,
            'closure'    => function ($row) {
                switch ($row->status) {
                    case self::STATUS_APPROVED:
                        return '<p class="label-active">'.trans('deliveryAgent::app.deliveryAgent.review.status.approved').'</p>';

                    case self::STATUS_PENDING:
                        return '<p class="label-pending">'.trans('deliveryAgent::app.deliveryAgent.review.status.pending').'</p>';

                    case self::STATUS_DISAPPROVED:
                        return '<p class="label-canceled">'.trans('deliveryAgent::app.deliveryAgent.review.status.disapproved').'</p>';
                }
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
                    'label' => $value,
                    'value' => (string) $value,
                ];
            }, range(1, 5)),
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('deliveryAgent::app.deliveryAgent.review.id'),
            'type'       => 'integer',
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
            'index'      => 'comment',
            'label'      => trans('deliveryAgent::app.deliveryAgent.review.comment'),
            'type'       => 'string',
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('deliveryAgent::app.deliveryAgent.review.created_at'),
            'type'            => 'date',
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
        if (bouncer()->hasPermission('delivery_agents.reviews.edit')) {
            $this->addAction([
                'index'  => 'edit',
                'icon'   => 'icon-edit',
                'title'  => trans('deliveryAgent::app.deliveryAgent.review.actions.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.review.edit', $row->id);
                },
            ]);
        }

//        if (bouncer()->hasPermission('delivery_agents.reviews.delete')) {
//            $this->addAction([
//                'index'  => 'delete',
//                'icon'   => 'icon-delete',
//                'title'  => trans('deliveryAgent::app.deliveryAgent.review.actions.delete'),
//                'method' => 'DELETE',
//                'url'    => function ($row) {
//                    return route('admin.delivery_agents.reviews.delete', $row->id);
//                },
//            ]);
//        }
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
//    public function prepareMassActions()
//    {
//        if (bouncer()->hasPermission('delivery_agents.reviews.delete')) {
//            $this->addMassAction([
//                'title'  => trans('deliveryAgent::app.deliveryAgent.review.actions.delete'),
//                'url'    => route('admin.delivery_agents.reviews.mass_delete'),
//                'method' => 'POST',
//            ]);
//        }
//
//        if (bouncer()->hasPermission('delivery_agents.reviews.edit')) {
//            $this->addMassAction([
//                'title'   => trans('deliveryAgent::app.deliveryAgent.review.actions.update-status'),
//                'method'  => 'POST',
//                'url'     => route('admin.delivery_agents.reviews.mass_update'),
//                'options' => [
//                    [
//                        'label' => trans('deliveryAgent::app.deliveryAgent.review.status.pending'),
//                        'value' => 'pending',
//                    ],
//                    [
//                        'label' => trans('deliveryAgent::app.deliveryAgent.review.status.approved'),
//                        'value' => 'approved',
//                    ],
//                    [
//                        'label' => trans('deliveryAgent::app.deliveryAgent.review.status.disapproved'),
//                        'value' => 'disapproved',
//                    ],
//                ],
//            ]);
//        }
//    }
}
