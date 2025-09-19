<?php

namespace Webkul\DeliveryAgents\GraphQL\Queries\App\DeliveryAgent;

use Illuminate\Support\Facades\DB;
use Webkul\DeliveryAgents\Models\DeliveryAgentOrder;
use Webkul\DeliveryAgents\Models\DeliveryAgentReview;

class StatsQuery
{
    /**
     * Get delivery agent statistics
     */
    public function stats($rootValue, array $args, $context)
    {
        $deliveryAgent = auth('delivery-agent-api')->user();
        
        if (!$deliveryAgent) {
            return null;
        }

        $deliveryAgentId = $deliveryAgent->id;

        // Get order statistics
        $totalOrders = DeliveryAgentOrder::where('delivery_agent_id', $deliveryAgentId)->count();
        $completedOrders = DeliveryAgentOrder::where('delivery_agent_id', $deliveryAgentId)
            ->where('status', DeliveryAgentOrder::STATUS_DELIVERED)->count();
        $pendingOrders = DeliveryAgentOrder::where('delivery_agent_id', $deliveryAgentId)
            ->whereIn('status', [
                DeliveryAgentOrder::STATUS_ASSIGNED_TO_AGENT,
                DeliveryAgentOrder::STATUS_ACCEPTED_BY_AGENT,
                DeliveryAgentOrder::STATUS_OUT_FOR_DELIVERY
            ])->count();
        $rejectedOrders = DeliveryAgentOrder::where('delivery_agent_id', $deliveryAgentId)
            ->where('status', DeliveryAgentOrder::STATUS_REJECTED_BY_AGENT)->count();

        // Get review statistics
        $totalReviews = DeliveryAgentReview::where('delivery_agent_id', $deliveryAgentId)->count();
        $averageRating = DeliveryAgentReview::where('delivery_agent_id', $deliveryAgentId)
            ->where('status', DeliveryAgentReview::STATUS_APPROVED)
            ->avg('rating') ?? 0;

        // Get time-based statistics
        $thisMonthOrders = DeliveryAgentOrder::where('delivery_agent_id', $deliveryAgentId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $thisWeekOrders = DeliveryAgentOrder::where('delivery_agent_id', $deliveryAgentId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $todayOrders = DeliveryAgentOrder::where('delivery_agent_id', $deliveryAgentId)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return (object) [
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'pending_orders' => $pendingOrders,
            'rejected_orders' => $rejectedOrders,
            'average_rating' => round($averageRating, 2),
            'total_reviews' => $totalReviews,
            'this_month_orders' => $thisMonthOrders,
            'this_week_orders' => $thisWeekOrders,
            'today_orders' => $todayOrders,
        ];
    }
}
