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
    ) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_agent_id' => 'required|integer|exists:delivery_agents,id',
            'state_area_id'     => 'required|integer|exists:state_areas,id',
        ]);

        $range = $this->deliveryAgentRepository->addRange($validated);

        if (! $range) {
            return response()->json([
                'message' => trans('deliveryAgent::app.range.create.create-failed'),
                'status'  => 'error',
            ], 422);
        }

        return response()->json([
            'message' => trans('deliveryAgent::app.range.create.create-success'),
            'status'  => 'success',
            'data'    => $range->load('state_area'),
        ]);

    }

    public function update(int $id, Request $request)
    {
        $validated = $request->validate([
            'delivery_agent_id' => 'required|integer|exists:delivery_agents,id',
            'state_area_id'     => 'required|integer|exists:state_areas,id',
        ]);

        $range = $this->deliveryAgentRepository->updateRange($id, $validated);

        if (! $range) {
            return response()->json([
                'message' => trans('deliveryAgent::app.range.edit.edit-failed'),
                'status'  => 'error',
            ], 422);
        }

        return response()->json([
            'message' => trans('deliveryAgent::app.range.edit.edit-success'),
            'status'  => 'success',
            'data'    => $range->load('state_area'),
        ]);

    }

    public function delete(int $id)
    {
        Event::dispatch('deliveryAgent.range.delete.before', $id);

        $deleted = $this->deliveryAgentRepository->removeRange($id);

        Event::dispatch('deliveryAgent.range.delete.after', $id);

        if (! $deleted) {
            return new JsonResponse([
                'message' => trans('deliveryAgent::app.range.view.range-delete-failed'),
                'status'  => 'error',
            ], 422);
        }

        return new JsonResponse([
            'message' => trans('deliveryAgent::app.range.view.range-delete-success'),
            'status'  => 'success',
        ]);
    }
}
