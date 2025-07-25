<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin;

use Webkul\DeliveryAgents\Datagrids\StateDataGrid;
use Webkul\DeliveryAgents\Repositories\StateRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\ValidationException;


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

    public function create(){

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
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
            'country_code'

        ]));

        $state = $this->stateRepository->create($data);
        return new JsonResponse([
            'data'    => $state,
            'message' => trans('deliveryagent::app.country.state.create.create-success'),
        ]);

    }

    public function show(Request $request,$id)
    {
        $state = $this->stateRepository->findOrFail($id);
        if ($request->ajax()) {
            return response()->json([
                'data' => $state
            ]);
        }
        return view('deliveryagents::admin.Countries.view.States.view', compact('state'));

    }
    public function edit(int $id)
    {
        $state = $this->stateRepository->findOrFail($id);
        return response()->json([
            'data' => $state,
        ]);

    }
    public function update(int $id)
    {
        $this->validate(request(), [
            'default_name'    => 'string|required|unique:country_states,default_name,'.$id,
        ]);
        $data = request()->only([
            'default_name',

        ]);
        $state = $this->stateRepository->update($data,$id);

        return new JsonResponse([
            'message' => trans('deliveryagent::app.country.state.edit.edit-success'),
            'data'    => [
                'state'=>$state->fresh()
            ],

        ]);

    }
    public function destroy()
    {

    }

}
