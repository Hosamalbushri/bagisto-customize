<?php

namespace Webkul\DeliveryAgents\Repositories;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;

class DeliveryAgentRepository extends Repository
{
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
                $dir = 'deliveryagent/'.$deliveryagent->id;

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

}
