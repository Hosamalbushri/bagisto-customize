<?php

namespace Webkul\DeliveryAgents\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Webkul\DeliveryAgents\Models\Order;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;

class DeliveryAgentService
{
    public function __construct(
        protected DeliveryAgentRepository $deliveryAgentRepository,
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository,
    ) {}

    /**
     * Validate delivery agent
     */
    public function validateAgent(int $agentId): bool
    {
        $agent = $this->deliveryAgentRepository->find($agentId);
        return $agent && (int) $agent->status === 1;
    }

    /**
     * Get valid order statuses
     */
    public function getValidStatuses(): array
    {
        return [
            Order::STATUS_ACCEPTED_BY_AGENT,
            Order::STATUS_REJECTED_BY_AGENT,
            Order::STATUS_OUT_FOR_DELIVERY,
            Order::STATUS_DELIVERED,
        ];
    }

    /**
     * Validate order status change
     */
    public function validateOrderStatusChange(Order $order, string $status, int $agentId): void
    {
        if (empty($order->delivery_agent_id) || (int) $order->delivery_agent_id !== $agentId) {
            throw new \Exception('deliveryAgent::app.select-order.update.updated-error');
        }

        if (!in_array($status, $this->getValidStatuses())) {
            throw new \Exception('deliveryAgent::app.select-order.update.updated-error');
        }

        if (!$this->validateAgent($agentId)) {
            throw new \Exception('deliveryAgent::app.select-order.create.create-error');
        }
    }

    /**
     * Assign order to delivery agent
     */
    public function assignOrderToAgent(int $orderId, int $deliveryAgentId): array
    {
        return DB::transaction(function () use ($orderId, $deliveryAgentId) {
            $order = $this->orderRepository->findOrFail($orderId);
            
            if (!$this->validateAgent($deliveryAgentId)) {
                throw new \Exception('deliveryAgent::app.select-order.create.create-error');
            }

            $order->update(['delivery_agent_id' => $deliveryAgentId]);

            $order->deliveryAssignments()->updateOrCreate(
                ['delivery_agent_id' => $deliveryAgentId],
                [
                    'status'      => Order::STATUS_ASSIGNED_TO_AGENT,
                    'assigned_at' => now(),
                ]
            );

            $this->handleInvoiceCreation($order, Order::STATUS_ASSIGNED_TO_AGENT);

            return [
                'success' => true,
                'message' => 'deliveryAgent::app.select-order.create.create-success'
            ];
        });
    }

    /**
     * Change order status
     */
    public function changeOrderStatus(int $orderId, string $status, int $deliveryAgentId): array
    {
        return DB::transaction(function () use ($orderId, $status, $deliveryAgentId) {
            $order = $this->orderRepository
                ->with(['deliveryAssignments', 'invoices', 'items'])
                ->findOrFail($orderId);

            $this->validateOrderStatusChange($order, $status, $deliveryAgentId);

            // Handle status-specific actions (includes order status update)
            $this->handleStatusSpecificActions($order, $status, $deliveryAgentId);

            return [
                'success' => true,
                'message' => 'deliveryAgent::app.select-order.update.update-success'
            ];
        });
    }

    /**
     * Handle invoice creation if needed
     */
    private function handleInvoiceCreation(Order $order, string $orderStatus): void
    {
        if ($order->invoices->isEmpty() && $order->canInvoice()) {
            $this->invoiceRepository->create(
                [
                    'order_id' => $order->id,
                    'invoice'  => [
                        'items' => $order->items->mapWithKeys(fn ($item) => [
                            $item->id => $item->qty_to_invoice,
                        ])->toArray(),
                    ],
                ],
                null,
                $orderStatus
            );
        } else {
            $this->orderRepository->updateOrderStatus($order, $orderStatus);
        }
    }

    /**
     * Handle status-specific actions
     */
    private function handleStatusSpecificActions(Order $order, string $status, int $deliveryAgentId): void
    {
        $deliveryAgent = $this->deliveryAgentRepository->find($deliveryAgentId);

        switch ($status) {
            case Order::STATUS_ACCEPTED_BY_AGENT:
                $this->updateAssignmentStatus($order, $status, [
                    'accepted_at' => now(),
                ]);
                $this->orderRepository->updateOrderStatus($order, $status);
                break;

            case Order::STATUS_REJECTED_BY_AGENT:
                $this->updateAssignmentStatus($order, $status, [
                    'rejected_at' => now(),
                ]);
                $order->update(['delivery_agent_id' => null]);
                $this->orderRepository->updateOrderStatus($order, $status);
                break;

            case Order::STATUS_OUT_FOR_DELIVERY:
                $this->updateAssignmentStatus($order, $status);
                $this->orderRepository->updateOrderStatus($order, $status);
                break;

            case Order::STATUS_DELIVERED:
                $this->updateAssignmentStatus($order, $status, [
                    'completed_at'        => now(),
                    'delivery_agent_info' => json_encode([
                        'id'    => $deliveryAgent->id,
                        'name'  => $deliveryAgent->name,
                        'phone' => $deliveryAgent->phone,
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE),
                ]);
                $order->update(['is_delivered' => true]);
                // Update to COMPLETED status directly for delivered orders
                $this->orderRepository->updateOrderStatus($order, Order::STATUS_COMPLETED);
                break;
        }
    }

    /**
     * Update assignment status
     */
    private function updateAssignmentStatus(Order $order, string $status, array $extra = []): void
    {
        $order->deliveryAssignments()
            ->where('delivery_agent_id', $order->delivery_agent_id)
            ->update(array_merge(['status' => $status], $extra));
    }

    /**
     * Handle exceptions with proper logging
     */
    public function handleException(\Throwable $e, string $context, array $data = []): array
    {
        DB::rollBack();
        
        Log::error($context, array_merge($data, [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]));

        return [
            'success' => false,
            'message' => $e->getMessage() ?: 'general.error',
            'code' => 500
        ];
    }
}
