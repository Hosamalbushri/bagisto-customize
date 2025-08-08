<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin\DeliveryAgents;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentRepository;
use Webkul\DeliveryAgents\Repositories\RangeRepository;

class RangesController extends Controller
{
    use ValidatesRequests;
    public function __construct(
        protected DeliveryAgentRepository $deliveryAgentRepository,
        protected RangeRepository $rangeRepository,

    ) {}
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

    public function updateRange(int $id)
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

    public function deleteRange(int $id)
    {
        Event::dispatch('deliveryAgent.range.delete.before', $id);

        $this->rangeRepository->delete($id);

        Event::dispatch('deliveryAgent.range.delete.after', $id);

        return new JsonResponse([
            'message' => trans('deliveryagent::app.range.view.range-delete-success'),
        ]);
    }
}
