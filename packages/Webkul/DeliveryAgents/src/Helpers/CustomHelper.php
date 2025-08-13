<?php
namespace Webkul\DeliveryAgents\Helpers;
use Illuminate\Support\Facades\DB;

class CustomHelper
{
    public static function groupedAreasByStates()
    {
        $collection = [];

        foreach (DB::table('state_areas')->get() as $area) {
            $collection[$area->country_state_id][] = $area;
        }

        return $collection;
    }
    public static function groupedStatesByCountries()
    {
        $collection = [];

        foreach (DB::table('country_states')->get() as $state) {
            $collection[$state->country_id][] = $state;
        }

        return $collection;
    }

}
