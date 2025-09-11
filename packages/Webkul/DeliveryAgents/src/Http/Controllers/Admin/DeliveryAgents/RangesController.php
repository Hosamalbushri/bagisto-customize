<?php

namespace Webkul\DeliveryAgents\Http\Controllers\Admin\DeliveryAgents;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\DeliveryAgents\Http\Requests\MassAddRequest;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentRepository;

class RangesController extends Controller
{
    use ValidatesRequests;

    public function __construct(
        protected DeliveryAgentRepository $deliveryAgentRepository,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'delivery_agent_id' => 'required|integer|exists:delivery_agents,id',
            'state_area_id'     => 'required|integer|exists:state_areas,id',
        ]);

        $deliveryAgent = $this->deliveryAgentRepository->find($validated['delivery_agent_id']);
        if (! $deliveryAgent) {
            return new JsonResponse([
                'message' => trans('deliveryAgent::app.range.create.create-failed'),
                'status'  => 'error',
            ], 422);
        }
        $allowMultiple = core()->getConfigData('delivery.settings.ranges.allow_multiple_ranges');

        if (! $allowMultiple && $deliveryAgent->ranges()->exists()) {
            return new JsonResponse([
                'message' => trans('deliveryAgent::app.range.create.multiple-not-allowed'),
                'status'  => 'error',
            ], 422);
        }

        $range = $this->deliveryAgentRepository->addRange($validated);

        if (! $range) {
            return new JsonResponse([
                'message' => trans('deliveryAgent::app.range.create.create-failed'),
                'status'  => 'error',
            ], 422);
        }

        return new JsonResponse([
            'message' => trans('deliveryAgent::app.range.create.create-success'),
            'status'  => 'success',
            'data'    => $range->load('state_area'),
        ]);
    }

    public function update(int $id, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'delivery_agent_id' => 'required|integer|exists:delivery_agents,id',
            'state_area_id'     => 'required|integer|exists:state_areas,id',
        ]);

        $range = $this->deliveryAgentRepository->updateRange($id, $validated);

        if (! $range) {
            return new JsonResponse([
                'message' => trans('deliveryAgent::app.range.edit.edit-failed'),
                'status'  => 'error',
            ], 422);
        }

        return new JsonResponse([
            'message' => trans('deliveryAgent::app.range.edit.edit-success'),
            'status'  => 'success',
            'data'    => $range->load('state_area'),
        ]);
    }

    public function delete(int $id): JsonResponse
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

    public function massAdd(MassAddRequest $massAddRequest, $areaId): JsonResponse
    {
        $selectedDeliveryAgentIds = $massAddRequest->input('indices');
        foreach ($selectedDeliveryAgentIds as $deliveryAgentId) {

            $this->deliveryAgentRepository->addRange([
                'delivery_agent_id' => $deliveryAgentId,
                'state_area_id'     => $areaId,
            ]);

        }

        return new JsonResponse([
            'message' => trans('deliveryAgent::app.country.state.area.view.dataGrid.add-success'),
        ]);
    }

    public function massDestroy(MassDestroyRequest $massDestroyRequest, $areaId): JsonResponse
    {
        $selectedDeliveryAgentIds = $massDestroyRequest->input('indices');
        foreach ($selectedDeliveryAgentIds as $deliveryAgentId) {
            $this->deliveryAgentRepository->massRemoveRange(
                $deliveryAgentId, $areaId
            );

        }

        return new JsonResponse([
            'message' => trans('deliveryAgent::app.country.state.area.view.dataGrid.deleted-success'),
        ]);
    }
}
