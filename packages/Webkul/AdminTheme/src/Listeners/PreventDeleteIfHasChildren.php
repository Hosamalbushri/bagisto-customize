<?php

namespace Webkul\AdminTheme\Listeners;

use Exception;
use Webkul\DeliveryAgents\Models\Areas;

class PreventDeleteIfHasChildren
{
    /**
     * Handle the event.
     *
     * @param  mixed  $model
     * @return void
     *
     * @throws \Exception
     */
    public function handle($model)
    {
        if (! property_exists($model, 'preventDeleteIfHasChildren')) {
            return; // لا توجد علاقات معرفة → السماح بالحذف
        }

        foreach ($model->preventDeleteIfHasChildren as $relation) {
            if (method_exists($model, $relation) && $model->{$relation}()->exists()) {
                throw new Exception(
                    "لا يمكن حذف هذا العنصر لأنه يحتوي على عناصر مرتبطة في علاقة '{$relation}'."
                );
            }
        }
    }
    public function beforeDeleteArea($id)
    {
        $area = Areas::with('ranges')->find($id);
        dd($area);
        if ($area->ranges()->exists()) {
            throw new Exception("لا يمكن حذف الفئة #{$area->id} لأنها تحتوي على فئات فرعية.");
        }
    }
}
