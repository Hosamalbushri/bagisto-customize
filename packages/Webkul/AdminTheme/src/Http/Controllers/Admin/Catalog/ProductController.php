<?php

namespace Webkul\AdminTheme\Http\Controllers\Admin\Catalog;
use Illuminate\Routing\Controller;
use Webkul\AdminTheme\Datagrids\Catalog\ProductDataGrid;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(ProductDataGrid::class)->process();
        }
        abort(404);
    }
}
