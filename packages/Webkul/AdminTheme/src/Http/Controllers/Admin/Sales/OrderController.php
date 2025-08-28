<?php

namespace Webkul\AdminTheme\Http\Controllers\Admin\Sales;

use Webkul\Admin\Http\Controllers\Controller;
use Webkul\AdminTheme\Datagrids\Sales\OrderDataGrid;

class OrderController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(OrderDataGrid::class)->process();
        }
        abort(404);
    }

}
