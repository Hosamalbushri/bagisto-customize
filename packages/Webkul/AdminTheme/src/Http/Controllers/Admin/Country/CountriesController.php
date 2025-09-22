<?php

namespace Webkul\AdminTheme\Http\Controllers\Admin\Country;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\AdminTheme\Datagrids\Country\CountryDataGrid;
use Webkul\AdminTheme\Repositories\Country\CountryRepository;
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

        return view('adminTheme::admin.Countries.index.index');
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
            'message' => trans('adminTheme::app.country.create.create-success'),
        ]);

    }

    public function edit(Request $request, $id)
    {
        $country = $this->counrtyRepository->findOrFail($id);
        $country->load('states');

        return view('adminTheme::admin.Countries.view', compact('country'));

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
        $country = $this->counrtyRepository->findOrFail($id);
        $country->update($data);

        return new JsonResponse([
            'message' => trans('adminTheme::app.country.edit.edit-success'),
            'data'    => [
                'country'=> $country->fresh(),
            ],

        ]);

    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->counrtyRepository->delete($id);

            return new JsonResponse(['message' => trans('adminTheme::app.country.dataGrid.delete-success')]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 400);
        }
    }

    public function massDelete(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        foreach ($indices as $index) {

            $this->counrtyRepository->delete($index);
        }

        return new JsonResponse([
            'message' => trans('adminTheme::app.country.dataGrid.mass-delete-success'),
        ]);

    }
}

