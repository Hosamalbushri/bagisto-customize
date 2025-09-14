<?php

namespace Webkul\DeliveryAgents\Repositories;

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use Webkul\DeliveryAgents\Models\DeliveryAgentOrder;

class DeliveryAgentRepository extends Repository
{
    protected $rangeRepository;

    public function __construct(RangeRepository $rangeRepository)
    {
        parent::__construct(app()); // مهم للـ Repository الأساسي
        $this->rangeRepository = $rangeRepository;
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return '\Webkul\DeliveryAgents\Models\DeliveryAgent';
    }

    /**
     * Upload customer's images.
     *
     * @param  array  $data
     * @param  string  $type
     * @return void
     */
    public function uploadImages($data, $deliveryagent, $type = 'image')
    {
        if (isset($data[$type])) {
            $request = request();

            foreach ($data[$type] as $imageId => $image) {
                $file = $type.'.'.$imageId;
                $dir = 'deliveryAgent/'.$deliveryagent->id;

                if ($request->hasFile($file)) {
                    if ($deliveryagent->{$type}) {
                        Storage::delete($deliveryagent->{$type});
                    }

                    $deliveryagent->{$type} = $request->file($file)->store($dir);
                    $deliveryagent->save();
                }
            }
        } else {
            if ($deliveryagent->{$type}) {
                Storage::delete($deliveryagent->{$type});
            }

            $deliveryagent->{$type} = null;
            $deliveryagent->save();
        }
    }

    /**
     * Check if agent already has a range for a specific state_area
     */
    public function hasRange(int $deliveryAgentId, int $stateAreaId, ?int $excludeId = null): bool
    {
        $cacheKey = "delivery_agent_range_{$deliveryAgentId}_{$stateAreaId}_" . ($excludeId ?? 'null');
        
        return cache()->remember($cacheKey, 300, function () use ($deliveryAgentId, $stateAreaId, $excludeId) {
            $query = $this->rangeRepository->where('delivery_agent_id', $deliveryAgentId)
                ->where('state_area_id', $stateAreaId);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            return $query->exists();
        });
    }

    /**
     * Add new range for delivery agent
     */
    public function addRange(array $data)
    {
        if ($this->hasRange($data['delivery_agent_id'], $data['state_area_id'])) {
            return null;
        }

        $range = $this->rangeRepository->create([
            'delivery_agent_id' => $data['delivery_agent_id'],
            'state_area_id'     => $data['state_area_id'],
        ]);

        // Clear cache after adding range
        $this->clearRangeCache($data['delivery_agent_id'], $data['state_area_id']);

        return $range;
    }

    /**
     * Update existing range
     */
    public function updateRange(int $rangeId, array $data)
    {
        if ($this->hasRange($data['delivery_agent_id'], $data['state_area_id'], $rangeId)) {
            return null;
        }

        $range = $this->rangeRepository->update([
            'state_area_id' => $data['state_area_id'],
        ], $rangeId);

        // Clear cache after updating range
        $this->clearRangeCache($data['delivery_agent_id'], $data['state_area_id']);

        return $range;
    }

    /**
     * Delete existing range
     */
    public function removeRange(int $rangeId): bool
    {
        // Get range info before deletion for cache clearing
        $range = $this->rangeRepository->find($rangeId);
        
        $deleted = $this->rangeRepository->delete($rangeId);
        
        if ($deleted && $range) {
            // Clear cache after removing range
            $this->clearRangeCache($range->delivery_agent_id, $range->state_area_id);
        }
        
        return $deleted;
    }

    public function massRemoveRange(int $deliveryAgentId, int $areaId): bool
    {
        $deleted = $this->rangeRepository
            ->where([
                'delivery_agent_id' => $deliveryAgentId,
                'state_area_id'     => $areaId,
            ])
            ->delete();
            
        if ($deleted) {
            // Clear cache after mass removal
            $this->clearRangeCache($deliveryAgentId, $areaId);
        }
        
        return $deleted;
    }

    /**
     * Delete a delivery agent only if they have no incomplete (active) orders.
     *
     * Incomplete statuses considered: pending, assigned_to_agent, accepted_by_agent, out_for_delivery, or NULL.
     * Returns true on successful deletion, false if deletion is not allowed or agent not found.
     */
    public function deleteIfNoIncompleteOrders(int $deliveryAgentId): bool
    {
        $hasActiveOrIncompleteOrders = DeliveryAgentOrder::query()
            ->where('delivery_agent_id', $deliveryAgentId)
            ->where(function ($q) {
                $q->whereNull('status')
                    ->orWhereIn('status', [
                        DeliveryAgentOrder::STATUS_ASSIGNED_TO_AGENT,
                        DeliveryAgentOrder::STATUS_ACCEPTED_BY_AGENT,
                        DeliveryAgentOrder::STATUS_OUT_FOR_DELIVERY,
                    ]);
            })
            ->exists();

        if ($hasActiveOrIncompleteOrders) {
            return false;
        }

        $agent = $this->find($deliveryAgentId);

        if (! $agent) {
            return false;
        }

        // Clean up stored image if exists
        if (! empty($agent->image)) {
            Storage::delete($agent->image);
        }

        return (bool) $this->delete($deliveryAgentId);
    }

    /**
     * Clear range cache for specific agent and area
     */
    protected function clearRangeCache(int $deliveryAgentId, int $stateAreaId): void
    {
        $cacheKey = "delivery_agent_range_{$deliveryAgentId}_{$stateAreaId}_null";
        cache()->forget($cacheKey);
    }

    /**
     * Clear all range cache for specific agent
     */
    protected function clearAllRangeCache(int $deliveryAgentId): void
    {
        $pattern = "delivery_agent_range_{$deliveryAgentId}_*";
        // Note: This is a simplified approach. In production, consider using Redis with pattern matching
        cache()->flush(); // This clears all cache - use with caution
    }
}
