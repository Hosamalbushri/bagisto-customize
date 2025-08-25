<?php

namespace Webkul\DeliveryAgents\Listeners;

use Webkul\DeliveryAgents\Models\Order;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;

class UpdateInOrderFields
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderItemRepository $orderItemRepository,

    ) {}

    public function handle($event)
    {
        // إذا الحدث أرسل ككائن Order
        if ($event instanceof Order) {
            $this->handleOrderObject($event);
        }
        // إذا الحدث أرسل كمصفوفة بيانات (مثل Order Items)
        elseif (is_array($event) && isset($event['items'])) {
            $this->handleOrderArray($event);
        }
    }

    /**
     * التعامل مع كائن Order
     */
    protected function handleOrderObject(Order $order): void
    {
        if (empty($order->delivery_agent_id)) {
            return;
        }

        $order->update([
            'delivery_status' => Order::STATUS_CANCELED,
        ]);

        $order->deliveryAssignments()
            ->where('delivery_agent_id', $order->delivery_agent_id)
            ->update(['status' => Order::STATUS_CANCELED]);
    }

    /**
     * التعامل مع مصفوفة Order Items
     */
    protected function handleOrderArray(array $data): void
    {
        foreach ($data['items'] as $orderItemId => $itemData) {
            // إيجاد الـ Order من الـ Order Item
            $orderItem = $this->orderItemRepository->find($orderItemId);
            if (! $orderItem) {
                continue;
            }

            $order = $orderItem->order;
            if (! $order || empty($order->delivery_agent_id)) {
                continue;
            }

            // إعادة تعيين الحقول
            $order->update([
                'delivery_status'   => null,
                'delivery_agent_id' => null,
            ]);
        }
    }
}
