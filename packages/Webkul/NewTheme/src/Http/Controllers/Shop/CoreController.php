<?php

use Webkul\Shop\Http\Controllers\API\APIController;

class CoreController extends APIController
{
    public function getAreas()
    {
        {
            return response()->json([
                'data' => myHelper()->groupedAreasByStatesCode(),
            ]);
        }
    }

}
