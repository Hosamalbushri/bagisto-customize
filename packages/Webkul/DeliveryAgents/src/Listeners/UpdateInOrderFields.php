<?php

namespace Webkul\DeliveryAgents\Listeners;

use Webkul\Sales\Repositories\OrderRepository;

class UpdateInOrderFields
{
    public function __construct(
        protected OrderRepository $orderRepository,
    ) {}

    public function handle(array $data)
    {
        $orderItemId = array_key_first($data['items']);
        if (isset($orderItemId)) {
            $order = $this->orderRepository->find($orderItemId);
            $order->delivery_agent_id = null;
            $order->delivery_status = null;
            $order->save();
        }
    }
}
