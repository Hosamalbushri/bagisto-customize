<?php

namespace Webkul\DeliveryAgents\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Sales\Models\Order as BaseModel;

class Order extends BaseModel
{
    public function delivery_agent(): BelongsTo
    {
        return $this->belongsTo(DeliveryAgent::class, 'delivery_agent_id');
    }
    public function canShip(): bool
    {
        foreach ($this->items as $item) {
            if (
                $item->canShip()
                && ! in_array($item->order->status, [
                    self::STATUS_CLOSED,
                    self::STATUS_FRAUD,
                ])
                && empty($item->order->delivery_agent_id) // إذا ما تم تعيين مندوب
            ) {
                return true;
            }
        }

        return false;
    }

    public function getStateIdFromCode(string $code): ?int
    {
        return State::getIdByCode($code);
    }
}
