<?php
namespace Webkul\DeliveryAgents\Helpers;
use Illuminate\Support\Facades\DB;
use Webkul\DeliveryAgents\Repositories\AreaRepository;
use Webkul\DeliveryAgents\Repositories\StateRepository;

class CustomHelper
{
    public function __construct(
        protected AreaRepository $areaRepository,
        protected StateRepository $stateRepository,


    )
    {}

    public static function groupedAreasByStates()
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
    public static function groupedStatesByCountries()
    {
        $collection = [];

        foreach (DB::table('country_states')->get() as $state) {
            $collection[$state->country_id][] = $state;
        }

        return $collection;
    }
    public  function State_Name($code)
    {

        $state = $this->stateRepository->findOneByField('code', $code);

        return $state ? $state->name : '';

    }


}
