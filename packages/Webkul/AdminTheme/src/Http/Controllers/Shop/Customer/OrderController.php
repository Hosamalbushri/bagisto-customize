<?php

namespace Webkul\AdminTheme\Http\Controllers\Shop\Customer;

use Webkul\Admin\Http\Controllers\Controller;
use Webkul\AdminTheme\Datagrids\Shop\OrderDataGrid;

class OrderController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(OrderDataGrid::class)->process();
        }
        abort(404);
    }
}
