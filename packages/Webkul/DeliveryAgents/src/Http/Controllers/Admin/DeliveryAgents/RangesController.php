<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin\DeliveryAgents;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentRepository;
use Webkul\DeliveryAgents\Repositories\RangeRepository;

class RangesController extends Controller
{
    use ValidatesRequests;

    public function __construct(
        protected DeliveryAgentRepository $deliveryAgentRepository,
        protected RangeRepository $rangeRepository,

    ) {}

    public function store(Request $request)
    {
        $this->validate($request, [
            'delivery_agent_id'    => 'required|integer|exists:delivery_agents,id',
            'state_area_id'        => 'required|integer|exists:state_areas,id',

        ]);

        $deliveryAgent = $this->deliveryAgentRepository->findOrFail($request->delivery_agent_id);

        $existingRecord = $deliveryAgent->ranges()
            ->where('state_area_id', $request->state_area_id)
            ->first();

        if ($existingRecord) {
            return response()->json([
                'message' => trans('deliveryagent::app.range.create.create-failed'),
                'status'  => 'error',
            ],422);
        }

        $deliveryAgent->ranges()->create([
            'state_area_id'       => $request->state_area_id,
        ]);
        $range = $deliveryAgent->ranges()->first();

        return response()->json([
            'message' => trans('deliveryagent::app.range.create.create-success'),
            'data'    => $range,
        ]);

    }

    public function update(int $id)
    {
        $this->validate(request(), [
            'state_area_id'        => 'required|integer|exists:state_areas,id',
        ]);
        $data = request()->only([
            'state_area_id',
        ]);
        $range = $this->rangeRepository->findOrFail($id);
        $duplicate = $this->rangeRepository->where('delivery_agent_id', $range->delivery_agent_id)
            ->where('state_area_id', $data['state_area_id'])
            ->where('id', '!=', $id)
            ->first();

        if ($duplicate) {
            return response()->json([
                'message' => trans('deliveryagent::app.range.edit.edit-failed'),
                'status'  => 'error',
            ], 422);
        }

        $range->update($data);

        return response()->json([
            'message' => trans('deliveryagent::app.range.edit.edit-success'),
        ]);

    }

    public function delete(int $id)
    {
        Event::dispatch('deliveryAgent.range.delete.before', $id);

        $this->rangeRepository->delete($id);

        Event::dispatch('deliveryAgent.range.delete.after', $id);

        return new JsonResponse([
            'message' => trans('deliveryagent::app.range.view.range-delete-success'),
        ]);
    }
}
