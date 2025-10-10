<?php

namespace Webkul\AdminTheme\Listeners\Sales;

use Illuminate\Support\Facades\Log;
use Webkul\AdminTheme\Helpers\FirebaseHelper;
use Webkul\Sales\Models\Order;

class OrderStatusUpdateListener
{
    /**
     * Order status constants
     */
    private const STATUS_PENDING = 'pending';

    private const STATUS_PENDING_PAYMENT = 'pending_payment';

    private const STATUS_PROCESSING = 'processing';

    private const STATUS_COMPLETED = 'completed';

    private const STATUS_CANCELED = 'canceled';

    private const STATUS_CLOSED = 'closed';

    private const STATUS_FRAUD = 'fraud';

    private const STATUS_ASSIGNED_TO_AGENT = 'assigned_to_agent';

    private const STATUS_ACCEPTED_BY_AGENT = 'accepted_by_agent';

    private const STATUS_REJECTED_BY_AGENT = 'rejected_by_agent';

    private const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';

    /**
     * Statuses that should skip notifications
     */
    private const SKIP_NOTIFICATION_STATUSES = [
        self::STATUS_ACCEPTED_BY_AGENT,
        self::STATUS_REJECTED_BY_AGENT,
    ];

    /**
     * Status label keys for translation
     */
    private const STATUS_LABEL_KEYS = [
        self::STATUS_PENDING               => 'adminTheme::app.notifications.push-notifications.order-status-update.pending',
        self::STATUS_PENDING_PAYMENT       => 'adminTheme::app.notifications.push-notifications.order-status-update.pending-payment',
        self::STATUS_PROCESSING            => 'adminTheme::app.notifications.push-notifications.order-status-update.processing',
        self::STATUS_COMPLETED             => 'adminTheme::app.notifications.push-notifications.order-status-update.completed',
        self::STATUS_CANCELED              => 'adminTheme::app.notifications.push-notifications.order-status-update.canceled',
        self::STATUS_CLOSED                => 'adminTheme::app.notifications.push-notifications.order-status-update.closed',
        self::STATUS_FRAUD                 => 'adminTheme::app.notifications.push-notifications.order-status-update.fraud',
        self::STATUS_ASSIGNED_TO_AGENT     => 'adminTheme::app.notifications.push-notifications.order-status-update.assigned_to_agent',
        self::STATUS_ACCEPTED_BY_AGENT     => 'adminTheme::app.notifications.push-notifications.order-status-update.accepted_by_agent',
        self::STATUS_REJECTED_BY_AGENT     => 'adminTheme::app.notifications.push-notifications.order-status-update.rejected_by_agent',
        self::STATUS_OUT_FOR_DELIVERY      => 'adminTheme::app.notifications.push-notifications.order-status-update.out_for_delivery',
    ];

    /**
     * Cached status translations
     */
    private static array $cachedTranslations = [];

    /**
     * Handle the event when order status is updated.
     */
    public function handle(Order $order): void
    {
        // Combined early return checks for better performance
        if (! $this->shouldSendNotification($order)) {
            return;
        }
        $customer = $order->customer;
        $this->sendOrderNotification($order, $customer);
    }

    /**
     * Check if notification should be sent (optimized combined checks)
     */
    private function shouldSendNotification(Order $order): bool
    {
        // Check if notifications are enabled
        if (! $this->areNotificationsEnabled()) {
            return false;
        }

        // Check for skipped statuses
        if (in_array($order->status, self::SKIP_NOTIFICATION_STATUSES, true)) {
            return false;
        }

        // Check Firebase configuration
        if (! FirebaseHelper::isConfigured()) {
            Log::warning('Firebase not configured');
            return false;
        }

        // Check customer and device token
        $customer = $order->customer;
        if (! $customer || empty($customer->device_token)) {
            return false;
        }

        return true;
    }

    /**
     * Send order notification to customer
     */
    private function sendOrderNotification(Order $order, $customer): void
    {
        $statusTranslation = $this->getStatusTranslation($order->status);

        $notificationData = [
            'title' => __('adminTheme::app.notifications.push-notifications.order-status-update.title'),
            'body'  => __('adminTheme::app.notifications.push-notifications.order-status-update.body', [
                'order_number' => $order->increment_id,
                'status'       => $statusTranslation,
            ]),
        ];

        $fieldData = [
            'type'              => 'order',
            'order_id'          => (string) $order->id,
            'order_number'      => $order->increment_id,
            'status'            => $order->status,
            'status_translated' => $statusTranslation,
            'title'             => $notificationData['title'],
            'body'              => $notificationData['body'],
        ];

        $this->sendNotificationToCustomer($fieldData, $notificationData, $customer->device_token);
    }

    /**
     * Check if order status notifications are enabled
     */
    private function areNotificationsEnabled(): bool
    {
        return (bool) core()->getConfigData('general.api.notification_settings.enable_order_status_notifications', false);
    }

    /**
     * Get translated status label (with caching)
     */
    private function getStatusTranslation(string $status): string
    {
        // Check cache first
        if (isset(self::$cachedTranslations[$status])) {
            return self::$cachedTranslations[$status];
        }

        $translationKey = self::STATUS_LABEL_KEYS[$status] ?? null;
        $translation = $translationKey ? __($translationKey, [], $status) : $status;

        // Cache the translation
        self::$cachedTranslations[$status] = $translation;

        return $translation;
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
        }
    }
}
