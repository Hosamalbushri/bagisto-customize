<?php

namespace Webkul\DeliveryAgents\Repositories;

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;

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
        $query = $this->rangeRepository->where('delivery_agent_id', $deliveryAgentId)
            ->where('state_area_id', $stateAreaId);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Add new range for delivery agent
     */
    public function addRange(array $data)
    {
        if ($this->hasRange($data['delivery_agent_id'], $data['state_area_id'])) {
            return null;
        }

        return $this->rangeRepository->create([
            'delivery_agent_id' => $data['delivery_agent_id'],
            'state_area_id'     => $data['state_area_id'],
        ]);
    }

    /**
     * Update existing range
     */
    public function updateRange(int $rangeId, array $data)
    {
        if ($this->hasRange($data['delivery_agent_id'], $data['state_area_id'], $rangeId)) {
            return null;
        }

        return $this->rangeRepository->update([
            'state_area_id' => $data['state_area_id'],
        ], $rangeId);
    }

    /**
     * Delete existing range
     */
    public function removeRange(int $rangeId): bool
    {
        return $this->rangeRepository->delete($rangeId);
    }
    public function massRemoveRange(int $deliveryAgentId, int $areaId): bool
    {
        return $this->rangeRepository
            ->where([
                'delivery_agent_id' => $deliveryAgentId,
                'state_area_id'    => $areaId,
            ])
            ->delete();
    }

}
