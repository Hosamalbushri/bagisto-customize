<?php
namespace Webkul\DeliveryAgents\Datagrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CountryDataGrid extends DataGrid
{

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('countries')
            ->addSelect(
                'countries.id as countries_id',
                'countries.code',
                'countries.name',
            );

        $this->addFilter('id', 'countries.id');
        $this->addFilter('code', 'countries.code');
        $this->addFilter('name', 'countries.name');

        return $queryBuilder;
    }

    public function prepareColumns()
    {

        $this->addColumn([
            'index' => 'countries_id',
            'label' => trans('deliveryagent::app.country.datagrid.id'),
            'type'  => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,

        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('deliveryagent::app.country.datagrid.name'),
            'type'  => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,

        ]);

        $this->addColumn([
            'index' => 'code',
            'label' => trans('deliveryagent::app.country.datagrid.code'),
            'type'  => 'string',
            'searchable' => true,
            'filterable' => true,

        ]);
    }
    public function prepareActions()
    {
        if (bouncer()->hasPermission('delivery.country')){
            $this->addAction([
                'icon'   => 'icon-sort-left', //
                'title'  =>   'عرض',
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.country.view', $row->countries_id);
                },
            ]);

        }
        if (bouncer()->hasPermission('delivery.country')) {

            $this->addAction([
                'icon' => 'icon-delete',
                'title' => 'delete',
                'method' => 'DELETE',
                'url' => function ($row) {
                    return route('admin.country.delete', $row->countries_id);
                },
            ]);
        }




    }
}
