<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Shop;

use Webkul\DeliveryAgents\Helpers\CustomHelper;
use Webkul\Shop\Http\Controllers\API\APIController;

class CoreController extends APIController
{
    public function getAreas()
    {
        return response()->json([
            'data' => CustomHelper::groupedAreasByStatesCode(),
        ]);
    }

}
