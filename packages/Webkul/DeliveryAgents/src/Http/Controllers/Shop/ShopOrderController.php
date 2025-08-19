<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Shop;

use Illuminate\Routing\Controller;
use Webkul\DeliveryAgents\Datagrids\Orders\Shop\ShopOrderDateGrid;

class ShopOrderController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(ShopOrderDateGrid::class)->process();
        }
        abort(404);
    }

}
