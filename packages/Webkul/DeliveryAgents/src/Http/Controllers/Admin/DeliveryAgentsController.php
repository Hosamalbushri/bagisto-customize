<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\DataGrid\Exceptions\InvalidDataGridException;
use Webkul\DeliveryAgents\Datagrids\DeliveryAgent\DeliveryAgentDataGrid;
use Webkul\DeliveryAgents\Datagrids\DeliveryAgent\Views\OrderDateGrid;
use Webkul\DeliveryAgents\Datagrids\Orders\SelectDeliveryAgentDataGrid;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentRepository;
use Webkul\DeliveryAgents\Repositories\RangeRepository;
use Webkul\Sales\Repositories\OrderRepository;

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
        protected RangeRepository $rangeRepository,
        protected OrderRepository $orderRepository

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

    public function show(Request $request, $id)
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
     * Show the form for editing the specified resource.
     *
     * @return JsonResponse
     */
    public function edit(int $id)
    {
        $deliveryAgent = $this->deliveryAgentRepository->findOrFail($id);

        return response()->json([
            'data' => $deliveryAgent,
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

    public function selectedDeliveryAgents()
    {
        if (request()->ajax()) {
            return app(SelectDeliveryAgentDataGrid::class)->process();
        }
        abort(404);

    }

    // *************** Delivery Agents Mothed ******************

    public function storeRange(Request $request)
    {
        $this->validate($request, [
            'delivery_agent_id'    => 'required|integer|exists:delivery_agents,id',
            'state'                => 'string|required',
            'country'              => 'string|required',
            'area_name'            => 'string|required',

        ]);
        $deliveryAgent = $this->deliveryAgentRepository->find($request->delivery_agent_id);
        $deliveryAgent->ranges()->create([
            'area_name'   => $request->area_name,
            'state'       => $request->state,
            'country'     => $request->country,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        $range = $deliveryAgent->ranges()->first();

        return response()->json([
            'message' => trans('deliveryagent::app.range.create.create-success'),
            'data'    => $range,
        ]);

    }

    public function updataRange(int $id)
    {
        $this->validate(request(), [
            'state'         => 'string|required',
            'country'       => 'string|required',
            'area_name'     => 'string|required',

        ]);
        $data = request()->only([
            'state',
            'country',
            'area_name',
        ]);
        $range = $this->rangeRepository->findOrFail($id);
        $range->update($data);

        return response()->json([
            'message' => trans('deliveryagent::app.range.edit.edit-success'),
            'data'    => $range,
        ]);

    }

    public function assignToAgent(Request $request)
    {
        $order = $this->orderRepository->findOrFail($request->order_id);
        if ($order->delivery_agent_id) {
            session()->flash('error', trans('deliveryagent::app.select-order.create.order-has-delivery'));
        } else {
            $deliveryAgent = $this->deliveryAgentRepository->find($request->delivery_agent_id);

            if ($deliveryAgent && $deliveryAgent->status == 1 && $deliveryAgent->ranges->count() > 0) {
                $order->delivery_agent_id = $request->delivery_agent_id;
                $order->save();
                session()->flash('success', trans('deliveryagent::app.select-order.create.create-success'));
            } else {
                session()->flash('error', trans('deliveryagent::app.select-order.create.create-error'));
            }

        }

        return redirect()->route('admin.sales.orders.view', $order->id);
    }
}
