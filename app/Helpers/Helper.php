<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Webkul\DeliveryAgents\Repositories\StateRepository;

class Helper
{
    public static function groupedStatesByCountries()
    {
        $collection = [];

        foreach (DB::table('country_states')->get() as $state) {
            $collection[$state->country_id][] = $state;
        }

        return $collection;
    }
    public function state_name($code): string
    {
        $state = app()->make(StateRepository::class)
            ->findOneByField('code', $code);

        return $state ? $state->default_name : '';
    }
    public function groupedAreasByStates()
    {
        $collection = [];

        foreach (DB::table('state_areas')->get() as $area) {
            $collection[$area->country_state_id][] = $area;
        }

        return $collection;
    }
    public static function groupedAreasByStatesCode()
    {
        $collection = [];

        foreach (DB::table('state_areas')->get() as $area) {
            $collection[$area->state_code][] = $area;
        }

        return $collection;
    }
    public function formatDate($date = null, $format = 'd-m-Y h:i:s A')
    {
        $channel = core()->getCurrentChannel();

        if (is_null($date)) {
            $date = Carbon::now();
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        $date->setTimezone($channel->timezone);

        return $date->translatedFormat($format);
    }
}
