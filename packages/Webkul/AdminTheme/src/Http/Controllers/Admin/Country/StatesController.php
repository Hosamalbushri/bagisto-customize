<?php

namespace Webkul\AdminTheme\Http\Controllers\Admin\Country;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\AdminTheme\Datagrids\Country\State\StateDataGrid;
use Webkul\AdminTheme\Repositories\Country\StateRepository;

class StatesController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(
        protected StateRepository $stateRepository,
    ) {}

    public function index()
    {
        if (request()->ajax()) {
            return app(StateDataGrid::class)->process();
        }

        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'default_name' => 'required|string|unique:country_states,default_name',
            'code'         => 'required|string|regex:/^[a-zA-Z]{1,6}$/|unique:country_states,code',
            'country_id'   => 'required|integer|exists:countries,id',
            'country_code' => 'nullable|string',
        ]);
        $data = array_merge([
            'code' => strtoupper($validated['code']),
        ], request()->only([
            'default_name',
            'country_id',
            'country_code',

        ]));

        $state = $this->stateRepository->create($data);

        return new JsonResponse([
            'data'    => $state,
            'message' => trans('adminTheme::app.country.state.create.create-success'),
        ]);

    }

    public function edit(Request $request, $id)
    {
        $state = $this->stateRepository->findOrFail($id);
        if ($request->ajax()) {
            return response()->json([
                'data' => $state,
            ]);
        }

        return view('adminTheme::admin.Countries.view.States.view', compact('state'));

    }

    public function update(int $id)
    {
        $this->validate(request(), [
            'default_name'    => 'string|required|unique:country_states,default_name,'.$id,
        ]);
        $data = request()->only([
            'default_name',

        ]);
        $state = $this->stateRepository->findOrFail($id);
        $state->update($data);

        return new JsonResponse([
            'message' => trans('adminTheme::app.country.state.edit.edit-success'),
            'data'    => [
                'state'=> $state->fresh(),
            ],

        ]);

    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->stateRepository->delete($id);

            return new JsonResponse(['message' => trans('adminTheme::app.country.state.dataGrid.delete-success')]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 400);
        }
    }

    public function massDelete(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        foreach ($indices as $index) {
            $this->stateRepository->delete($index);
        }

        return new JsonResponse([
            'message' => trans('adminTheme::app.country.state.dataGrid.mass-delete-success'),
        ]);

    }
}

