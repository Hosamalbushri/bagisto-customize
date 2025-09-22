<?php

namespace Webkul\AdminTheme\Http\Controllers\Admin\Country;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\AdminTheme\Datagrids\Country\Areas\AreaDataGrid;
use Webkul\AdminTheme\Datagrids\Country\Areas\view\DeliveryAgentDataGrid;
use Webkul\AdminTheme\Repositories\Country\AreaRepository;

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
            'message' => trans('adminTheme::app.country.state.area.create.create-success'),
        ]);

    }

    public function view(int $id)
    {
        if (request()->ajax()) {
            $mode = request()->get('mode', 'in');
            return datagrid(DeliveryAgentDataGrid::class)->setMode($mode)->process();
        }
        $Area = $this->areaRepository->findOrFail($id);

        return view('adminTheme::admin.Countries.view.States.Areas.view', compact('Area'));
    }

    public function edit($id): JsonResponse
    {
        $area = $this->areaRepository->findOrFail($id);

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
            'message' => trans('adminTheme::app.country.state.area.edit.edit-success'),
        ]);

    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->areaRepository->delete($id);

            return new JsonResponse(['message' => trans('adminTheme::app.country.state.area.datagrid.delete-success')]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 400);
        }
    }
}

