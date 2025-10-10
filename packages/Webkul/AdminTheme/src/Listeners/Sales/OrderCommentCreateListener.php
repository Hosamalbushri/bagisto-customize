<?php

namespace Webkul\AdminTheme\Listeners\Sales;

use Illuminate\Support\Facades\Log;
use Webkul\AdminTheme\Helpers\FirebaseHelper;
use Webkul\Sales\Models\OrderComment;

class OrderCommentCreateListener
{
    /**
     * Handle the event when order comment is created.
     */
    public function handle(OrderComment $orderComment): void
    {
        // Combined early return checks for better performance
        if (! $this->shouldSendNotification($orderComment)) {
            return;
        }

        $order = $orderComment->order;
        $customer = $order->customer;

        $this->sendCommentNotification($orderComment, $order, $customer);
    }

    /**
     * Check if notification should be sent (optimized combined checks)
     */
    private function shouldSendNotification(OrderComment $orderComment): bool
    {
        // Check if notifications are enabled
        if (! $this->areNotificationsEnabled()) {
            return false;
        }

        // Check if customer should be notified
        if (! $this->shouldNotifyCustomer($orderComment)) {
            return false;
        }

        // Check Firebase configuration
        if (! FirebaseHelper::isConfigured()) {
            Log::warning('Firebase not configured');
            return false;
        }

        // Check if order and customer exist
        $order = $orderComment->order;
        if (! $order) {
            return false;
        }

        $customer = $order->customer;
        if (! $customer || empty($customer->device_token)) {
            return false;
        }

        return true;
    }

    /**
     * Send comment notification to customer
     */
    private function sendCommentNotification(OrderComment $orderComment, $order, $customer): void
    {
        $notificationData = [
            'title' => __('adminTheme::app.notifications.push-notifications.order-comment.title'),
            'body'  => __('adminTheme::app.notifications.push-notifications.order-comment.body', [
                'order_number' => $order->increment_id,
                'comment'      => $this->truncateComment($orderComment->comment),
            ]),
        ];

        $fieldData = [
            'type'         => 'order_comment',
            'order_id'     => (string) $order->id,
            'order_number' => $order->increment_id,
            'comment_id'   => (string) $orderComment->id,
            'comment'     => $orderComment->comment,
            'title'        => $notificationData['title'],
            'body'         => $notificationData['body'],
        ];

        $this->sendNotificationToCustomer($fieldData, $notificationData, $customer->device_token);
    }

    /**
     * Check if order comment notifications are enabled
     */
    private function areNotificationsEnabled(): bool
    {
        return (bool) core()->getConfigData('general.api.notification_settings.enable_order_comment_notifications', false);
    }

    /**
     * Check if customer should be notified based on customer_notified field
     */
    private function shouldNotifyCustomer(OrderComment $orderComment): bool
    {
        // Check if customer_notified field is set to 1
        if (! isset($orderComment->customer_notified) || $orderComment->customer_notified != 1) {
            return false;
        }

        return true;
    }

    /**
     * Truncate comment for notification display
     */
    private function truncateComment(string $comment, int $maxLength = 100): string
    {
        if (strlen($comment) <= $maxLength) {
            return $comment;
        }

        return substr($comment, 0, $maxLength) . '...';
    }

    /**
     * Send notification to customer device
     */
    private function sendNotificationToCustomer(array $fieldData, array $data, string $deviceToken): void
    {
        // Validate device token before sending
        if (empty($deviceToken) || strlen($deviceToken) < 50) {
            Log::warning('Invalid device token');
            return;
        }

        $notification = [
            'body'  => $data['body'],
            'title' => $data['title'],
        ];

        $success = auth_firebase_helper()->sendToDevice($deviceToken, $notification, $fieldData);

        if (! $success) {
            Log::error('Failed to send notification');
        } else {
            Log::info('Notification sent');
        }
    }
}
