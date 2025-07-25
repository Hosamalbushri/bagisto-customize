<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin;

use Webkul\DeliveryAgents\Datagrids\CountryDataGrid;
use Webkul\DeliveryAgents\Repositories\CountryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Webkul\DataGrid\Exceptions\InvalidDataGridException;

class CountriesController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Static pagination count.
     *
     * @var int
     */
    public const COUNT = 10;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected CountryRepository $counrtRepository,
    ) {}



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     * @throws InvalidDataGridException
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(CountryDataGrid::class)->process();
        }
        return view('deliveryagents::admin.Countries.index.index');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'name'    => 'string|required|unique:countries,name',
            'code'     => 'string|required|regex:/^[a-zA-Z]{1,6}$/|unique:countries,code',
        ]);
        $data = array_merge([
            'code' => strtoupper(request('code')),
        ], request()->only([
            'name',

        ]));
        $country = $this->counrtRepository->create($data);
        return new JsonResponse([
            'data'    => $country,
            'message' => trans('deliveryagent::app.country.create.create-success'),
        ]);

    }

    public function show(Request $request,$id)
    {
        $country = $this->counrtRepository->findOrFail($id);
        if ($request->ajax()) {
            return response()->json([
                'data' => $country
            ]);
        }
        return view('deliveryagents::admin.Countries.view', compact('country'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return JsonResponse
     */
    public function edit(int $id)
    {
        $country = $this->counrtRepository->findOrFail($id);
        return response()->json([
            'data' => $country,
        ]);

    }


    /**
     * Update the specified resource in storage.
     *
     * @return JsonResponse
     */
    public function update(int $id)
    {
        $this->validate(request(), [
            'name'    => 'string|required|unique:countries,name,'.$id,
        ]);
        $data = request()->only([
            'name',

        ]);
        $country = $this->counrtRepository->update($data,$id);

        return new JsonResponse([
            'message' => trans('deliveryagent::app.country.edit.edit-success'),
            'data'    => [
                'country'=>$country->fresh()
            ],

        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
//        $deliveryAgent = $this->deliveryAgentRepository->find($id);
//
//        if (! $deliveryAgent) {
//            return response()->json(['message' => trans('deliveryagent::app.deliveryagents.delete.unsuccessful_deletion_message')], 404);
//        }
//
//        $this->deliveryAgentRepository->delete($id);
//
//        return response()->json(['message' => trans('deliveryagent::app.deliveryagents.delete.successful_deletion_message')]);

    }
}
