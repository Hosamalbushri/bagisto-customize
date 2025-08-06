<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\DataGrid\Exceptions\InvalidDataGridException;
use Webkul\DeliveryAgents\Datagrids\CountryDataGrid;
use Webkul\DeliveryAgents\Repositories\CountryRepository;

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
        protected CountryRepository $counrtyRepository,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     *
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
            'name'     => 'string|required|unique:countries,name',
            'code'     => 'string|required|regex:/^[a-zA-Z]{1,6}$/|unique:countries,code',
        ]);
        $data = array_merge([
            'code' => strtoupper(request('code')),
        ], request()->only([
            'name',
        ]));
        $country = $this->counrtyRepository->create($data);

        return new JsonResponse([
            'data'    => $country,
            'message' => trans('deliveryagent::app.country.create.create-success'),
        ]);

    }

    public function edit(Request $request, $id)
    {
        $country = $this->counrtyRepository->findOrFail($id);
        $country->load('states');
        return view('deliveryagents::admin.Countries.view', compact('country'));

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
        $country = $this->counrtyRepository->update($data, $id);

        return new JsonResponse([
            'message' => trans('deliveryagent::app.country.edit.edit-success'),
            'data'    => [
                'country'=> $country->fresh(),
            ],

        ]);

    }

    /**
     * To delete the previously create CMS page.
     */
    public function delete(int $id): JsonResponse
    {
        try {
            Event::dispatch('country.before.delete', $id);
            $this->counrtyRepository->delete($id);
            Event::dispatch('country.after.delete', $id);

            return new JsonResponse(['message' => trans('deliveryagent::app.country.datagrid.delete-success')]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => trans('deliveryagent::app.country.datagrid.no-resource')], 400);
        }
    }

    public function massDelete(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        foreach ($indices as $index) {

            Event::dispatch('country.before.delete', $index);
            $this->counrtyRepository->delete($index);
            Event::dispatch('country.after.delete', $index);

        }

        return new JsonResponse([
            'message' => trans('deliveryagent::app.country.datagrid.mass-delete-success'),
        ]);

    }
}
