<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin\DeliveryAgents;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\DataGrid\Exceptions\InvalidDataGridException;
use Webkul\DeliveryAgents\Datagrids\DeliveryAgent\DeliveryAgentDataGrid;
use Webkul\DeliveryAgents\Datagrids\DeliveryAgent\Views\OrderDateGrid;
use Webkul\DeliveryAgents\Datagrids\Orders\SelectDeliveryAgentDataGrid;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentRepository;

class DeliveryAgentsController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Static pagination count.
     *
     * @var int
     */
    public const COUNT = 10;

    const ORDERS = 'orders';

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected DeliveryAgentRepository $deliveryAgentRepository,

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
            return app(DeliveryAgentDataGrid::class)->process();
        }

        return view('deliveryagents::admin.deliveryagents.index.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'first_name'    => 'string|required',
            'last_name'     => 'string|required',
            'gender'        => 'required',
            'email'         => 'required|unique:delivery_agents,email',
            'password'      => 'required|min:6|confirmed',
            'date_of_birth' => 'date|before:today',
            'phone'         => ['unique:delivery_agents,phone', new PhoneNumber],
        ]);

        $password = rand(100000, 10000000);
        $data = array_merge([
            'password'    => bcrypt($password),
            'is_verified' => 1,
        ], request()->only([
            'first_name',
            'last_name',
            'gender',
            'email',
            'date_of_birth',
            'phone',
        ]));
        if (empty($data['phone'])) {
            $data['phone'] = null;
        }
        $deliveryAgent = $this->deliveryAgentRepository->create($data);

        return new JsonResponse([
            'data'    => $deliveryAgent,
            'message' => trans('deliveryagent::app.deliveryagents.create.create-success'),
        ]);
    }

    public function view(Request $request, $id)
    {
        $deliveryAgent = $this->deliveryAgentRepository->with(['ranges', 'orders'])->findOrFail($id);
        if ($request->ajax()) {
            switch (request()->query('type')) {
                case self::ORDERS:
                    return datagrid(OrderDateGrid::class)->process();

            }

            return response()->json([
                'data'      => $deliveryAgent,
            ]);

        }

        return view('deliveryagents::admin.deliveryagents.view', compact('deliveryAgent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return JsonResponse
     */
    public function update(int $id)
    {
        $this->validate(request(), [
            'first_name'     => 'string|required',
            'last_name'      => 'string|required',
            'gender'         => 'required',
            'email'          => 'required|unique:delivery_agents,email,'.$id,
            'password'       => 'nullable|min:6|confirmed',
            'date_of_birth'  => 'date|before:today',
            'phone'          => ['unique:delivery_agents,phone,'.$id, new PhoneNumber],
            'status'         => 'required|boolean',
        ]);

        $data = request()->only([
            'first_name',
            'last_name',
            'gender',
            'email',
            'date_of_birth',
            'phone',
            'status',
        ]);
        if (request()->filled('password')) {
            $data['password'] = bcrypt(request('password'));
        }

        $deliveryAgent = $this->deliveryAgentRepository->update($data, $id);

        return new JsonResponse([
            'message' => trans('deliveryagent::app.deliveryagents.edit.edit-success'),
            'data'    => [
                'deliveryagent'=> $deliveryAgent->fresh(),
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
        $deliveryAgent = $this->deliveryAgentRepository->find($id);

        if (! $deliveryAgent) {
            return response()->json(['message' => trans('deliveryagent::app.deliveryagents.delete.unsuccessful_deletion_message')], 404);
        }

        $this->deliveryAgentRepository->delete($id);

        return response()->json(['message' => trans('deliveryagent::app.deliveryagents.delete.successful_deletion_message')]);

    }
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $deliveryAgents = $this->deliveryAgentRepository->findWhereIn('id', $massDestroyRequest->input('indices'));

        try {
            /**
             * Ensure that deliveryAgents do not have any active orders before performing deletion.
             */
//            foreach ($deliveryAgents as $deliveryAgent) {
//                if ($this->deliveryAgentRepository->haveActiveOrders($deliveryAgent)) {
//                    throw new \Exception(trans('admin::app.customers.customers.index.datagrid.order-pending'));
//                }
//            }

            /**
             * After ensuring that they have no active orders delete the corresponding customer.
             */
            foreach ($deliveryAgents as $deliveryAgent) {
                Event::dispatch('deliveryAgent.delete.before', $deliveryAgent);

                $this->deliveryAgentRepository->delete($deliveryAgent->id);

                Event::dispatch('deliveryAgent.delete.after', $deliveryAgent);
            }

            return new JsonResponse([
                'message' => trans('deliveryagent::app.deliveryagents.datagrid.delete-success'),
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 500);
        }

    }
    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $selectedDeliveryAgentIds = $massUpdateRequest->input('indices');

        foreach ($selectedDeliveryAgentIds as $deliveryAgentId) {
            Event::dispatch('deliveryAgent.update.before', $deliveryAgentId);

            $deliveryAgent = $this->deliveryAgentRepository->update([
                'status' => $massUpdateRequest->input('value'),
            ], $deliveryAgentId);

            Event::dispatch('deliveryAgent.update.after', $deliveryAgent);
        }

        return new JsonResponse([
            'message' => trans('deliveryagent::app.deliveryagents.datagrid.update-success'),
        ]);

    }

    public function selectedDeliveryAgents()
    {
        if (request()->ajax()) {
            return app(SelectDeliveryAgentDataGrid::class)->process();
        }
        abort(404);

    }





}
