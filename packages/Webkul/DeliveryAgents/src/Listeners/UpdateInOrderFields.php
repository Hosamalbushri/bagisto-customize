<?php

namespace Webkul\DeliveryAgents\Listeners;
use Webkul\DeliveryAgents\Models\Order;

class UpdateInOrderFields
{
    /**
     * Handle before cancel order event.
     */
    public function beforeCancelOrder(Order $order): void
    {
        if (! $order->delivery_agent_id) {
            return;
        }

        $this->updateOrderAndAssignments($order, Order::STATUS_CANCELED);
    }

    /**
     * Handle after save shipment event.
     */
    public function afterSaveShipment($event): void
    {
        $order = $event->order ?? null;

        if (! $order || ! $order->delivery_agent_id) {
            return;
        }

        $order->update([
            'delivery_status'   => null,
            'delivery_agent_id' => null,
        ]);
    }

    /**
     * Handle after save refund event.
     */

    public function afterSaveRefund($event): void
    {
        $order = $event->order ?? null;

        if (! $order || ! $order->delivery_agent_id) {
            return;
        }

        $this->updateOrderAndAssignments($order, Order::STATUS_CLOSED);
    }

    protected function updateOrderAndAssignments(Order $order, string $status): void
    {
        $order->update([
            'delivery_status' => $status,
        ]);

        $order->deliveryAssignments()
            ->where('delivery_agent_id', $order->delivery_agent_id)
            ->update(['status' => $status]);
    }
}
