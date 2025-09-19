<?php

namespace Webkul\DeliveryAgents\GraphQL\Mutations\App\DeliveryAgent;

use Illuminate\Support\Facades\DB;
use Webkul\DeliveryAgents\Models\DeliveryAgentOrder;
use Webkul\DeliveryAgents\Repositories\DeliveryAgentOrders;

class OrderMutation
{
    /**
     * @var DeliveryAgentOrders
     */
    protected $deliveryAgentOrdersRepository;

    public function __construct(
        DeliveryAgentOrders $deliveryAgentOrdersRepository
    ) {
        $this->deliveryAgentOrdersRepository = $deliveryAgentOrdersRepository;
    }

    /**
     * Accept order
     */
    public function accept($rootValue, array $args, $context)
    {
        try {
            $deliveryAgent = auth('delivery-agent-api')->user();
            
            if (!$deliveryAgent) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.orders.errors.unauthorized'),
                    'order' => null
                ];
            }

            $orderId = $args['input']['order_id'];
            
            $deliveryAgentOrder = DeliveryAgentOrder::where('order_id', $orderId)
                ->where('delivery_agent_id', $deliveryAgent->id)
                ->where('status', DeliveryAgentOrder::STATUS_ASSIGNED_TO_AGENT)
                ->first();

            if (!$deliveryAgentOrder) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.orders.errors.order_not_found'),
                    'order' => null
                ];
            }

            DB::beginTransaction();

            $deliveryAgentOrder->update([
                'status' => DeliveryAgentOrder::STATUS_ACCEPTED_BY_AGENT,
                'accepted_at' => now(),
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => __('deliveryAgent::app.orders.success.accepted'),
                'order' => $deliveryAgentOrder->load(['order', 'deliveryAgent', 'reviews'])
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'order' => null
            ];
        }
    }

    /**
     * Reject order
     */
    public function reject($rootValue, array $args, $context)
    {
        try {
            $deliveryAgent = auth('delivery-agent-api')->user();
            
            if (!$deliveryAgent) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.orders.errors.unauthorized'),
                    'order' => null
                ];
            }

            $orderId = $args['input']['order_id'];
            $reason = $args['input']['reason'] ?? null;
            
            $deliveryAgentOrder = DeliveryAgentOrder::where('order_id', $orderId)
                ->where('delivery_agent_id', $deliveryAgent->id)
                ->where('status', DeliveryAgentOrder::STATUS_ASSIGNED_TO_AGENT)
                ->first();

            if (!$deliveryAgentOrder) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.orders.errors.order_not_found'),
                    'order' => null
                ];
            }

            DB::beginTransaction();

            $deliveryAgentOrder->update([
                'status' => DeliveryAgentOrder::STATUS_REJECTED_BY_AGENT,
                'rejected_at' => now(),
                'delivery_agent_info' => $reason ? json_encode(['rejection_reason' => $reason]) : null,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => __('deliveryAgent::app.orders.success.rejected'),
                'order' => $deliveryAgentOrder->load(['order', 'deliveryAgent', 'reviews'])
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'order' => null
            ];
        }
    }

    /**
     * Update order status
     */
    public function updateStatus($rootValue, array $args, $context)
    {
        try {
            $deliveryAgent = auth('delivery-agent-api')->user();
            
            if (!$deliveryAgent) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.orders.errors.unauthorized'),
                    'order' => null
                ];
            }

            $orderId = $args['input']['order_id'];
            $status = $args['input']['status'];
            $notes = $args['input']['notes'] ?? null;
            
            $deliveryAgentOrder = DeliveryAgentOrder::where('order_id', $orderId)
                ->where('delivery_agent_id', $deliveryAgent->id)
                ->first();

            if (!$deliveryAgentOrder) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.orders.errors.order_not_found'),
                    'order' => null
                ];
            }

            // Validate status transition
            if (!$this->isValidStatusTransition($deliveryAgentOrder->status, $status)) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.orders.errors.invalid_status_transition'),
                    'order' => null
                ];
            }

            DB::beginTransaction();

            $updateData = ['status' => $status];
            
            if ($notes) {
                $updateData['delivery_agent_info'] = $notes;
            }

            // Set specific timestamps based on status
            switch ($status) {
                case DeliveryAgentOrder::STATUS_OUT_FOR_DELIVERY:
                    $updateData['accepted_at'] = now();
                    break;
                case DeliveryAgentOrder::STATUS_DELIVERED:
                    $updateData['completed_at'] = now();
                    break;
            }

            $deliveryAgentOrder->update($updateData);

            DB::commit();

            return [
                'success' => true,
                'message' => __('deliveryAgent::app.orders.success.status_updated'),
                'order' => $deliveryAgentOrder->load(['order', 'deliveryAgent', 'reviews'])
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'order' => null
            ];
        }
    }

    /**
     * Complete order
     */
    public function complete($rootValue, array $args, $context)
    {
        try {
            $deliveryAgent = auth('delivery-agent-api')->user();
            
            if (!$deliveryAgent) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.orders.errors.unauthorized'),
                    'order' => null
                ];
            }

            $orderId = $args['input']['order_id'];
            $deliveryNotes = $args['input']['delivery_notes'] ?? null;
            $customerSignature = $args['input']['customer_signature'] ?? null;
            $deliveryPhoto = $args['input']['delivery_photo'] ?? null;
            
            $deliveryAgentOrder = DeliveryAgentOrder::where('order_id', $orderId)
                ->where('delivery_agent_id', $deliveryAgent->id)
                ->whereIn('status', [
                    DeliveryAgentOrder::STATUS_ACCEPTED_BY_AGENT,
                    DeliveryAgentOrder::STATUS_OUT_FOR_DELIVERY
                ])
                ->first();

            if (!$deliveryAgentOrder) {
                return [
                    'success' => false,
                    'message' => __('deliveryAgent::app.orders.errors.order_not_found'),
                    'order' => null
                ];
            }

            DB::beginTransaction();

            $deliveryInfo = [];
            if ($deliveryNotes) {
                $deliveryInfo['delivery_notes'] = $deliveryNotes;
            }
            if ($customerSignature) {
                $deliveryInfo['customer_signature'] = $customerSignature;
            }
            if ($deliveryPhoto) {
                $deliveryInfo['delivery_photo'] = $deliveryPhoto;
            }

            $deliveryAgentOrder->update([
                'status' => DeliveryAgentOrder::STATUS_DELIVERED,
                'completed_at' => now(),
                'delivery_agent_info' => json_encode($deliveryInfo),
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => __('deliveryAgent::app.orders.success.completed'),
                'order' => $deliveryAgentOrder->load(['order', 'deliveryAgent', 'reviews'])
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'order' => null
            ];
        }
    }

    /**
     * Check if status transition is valid
     */
    private function isValidStatusTransition($currentStatus, $newStatus)
    {
        $validTransitions = [
            DeliveryAgentOrder::STATUS_ASSIGNED_TO_AGENT => [
                DeliveryAgentOrder::STATUS_ACCEPTED_BY_AGENT,
                DeliveryAgentOrder::STATUS_REJECTED_BY_AGENT
            ],
            DeliveryAgentOrder::STATUS_ACCEPTED_BY_AGENT => [
                DeliveryAgentOrder::STATUS_OUT_FOR_DELIVERY,
                DeliveryAgentOrder::STATUS_DELIVERED
            ],
            DeliveryAgentOrder::STATUS_OUT_FOR_DELIVERY => [
                DeliveryAgentOrder::STATUS_DELIVERED
            ]
        ];

        return in_array($newStatus, $validTransitions[$currentStatus] ?? []);
    }
}
