<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin\Country;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Webkul\DeliveryAgents\Datagrids\Country\AreaDataGrid;
use Webkul\DeliveryAgents\Repositories\AreaRepository;

class AreasController extends Controller
{
    use ValidatesRequests;

    public function __construct(
        protected AreaRepository $areaRepository,
    ) {}

    public function index()
    {
        if (request()->ajax()) {
            return app(AreaDataGrid::class)->process();
        }
        abort(404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_name'          => 'required|string',
            'country_state_id'   => 'required|integer|exists:country_states,id',
            'state_code'         => 'nullable|string',
            'country_code'       => 'nullable|string',

        ]);
        $data = array_merge([
            'state_code'   => strtoupper($validated['state_code']),
            'country_code' => strtoupper($validated['country_code']),

        ], request()->only([
            'area_name',
            'country_state_id',
            'state_code',
            'country_code',

        ]));

        $state = $this->areaRepository->create($data);

        return new JsonResponse([
            'data'    => $state,
            'message' => trans('deliveryagent::app.country.state.area.create.create-success'),
        ]);

    }

    public function edit($id): JsonResponse
    {
        $area = $this->areaRepository->findOrFail($id);

        //        return new JsonResponse([
        //            'area'  => $area,
        //        ]);
        return response()->json([
            'area' => $area,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $id = request()->id;

        $validated = $request->validate([
            'area_name'          => 'required|string',
        ]);
        $data = array_merge(request()->only([
            'area_name',
        ]));

        $state = $this->areaRepository->update($data, $id);

        return new JsonResponse([
            'data'    => $state,
            'message' => trans('deliveryagent::app.country.state.area.edit.edit-success'),
        ]);

    }

    public function delete(int $id): JsonResponse
    {
        try {
            Event::dispatch('area.before.delete', $id);
            $this->areaRepository->delete($id);
            Event::dispatch('area.after.delete', $id);

            return new JsonResponse(['message' => trans('deliveryagent::app.country.state.area.datagrid.delete-success')]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => trans('deliveryagent::app.country.state.area.datagrid.no-resource')], 400);
        }
    }
}
