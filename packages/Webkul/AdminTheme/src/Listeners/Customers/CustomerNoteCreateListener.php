<?php

namespace Webkul\AdminTheme\Listeners\Customers;

use Illuminate\Support\Facades\Log;
use Webkul\AdminTheme\Helpers\FirebaseHelper;
use Webkul\Customer\Models\CustomerNote;

class CustomerNoteCreateListener
{
    /**
     * Handle the event when customer note is created.
     */
    public function handle(CustomerNote $customerNote): void
    {
        // Combined early return checks for better performance
        if (! $this->shouldSendNotification($customerNote)) {
            return;
        }

        $customer = $customerNote->customer;
        
        $this->sendNoteNotification($customerNote, $customer);
    }

    /**
     * Check if notification should be sent (optimized combined checks)
     */
    private function shouldSendNotification(CustomerNote $customerNote): bool
    {
        // Check if notifications are enabled
        if (! $this->areNotificationsEnabled()) {
            return false;
        }

        // Check if customer should be notified
        if (! $this->shouldNotifyCustomer($customerNote)) {
            return false;
        }

        // Check Firebase configuration
        if (! FirebaseHelper::isConfigured()) {
            Log::warning('Firebase not configured');
            return false;
        }

        // Check if customer exists and has device token
        $customer = $customerNote->customer;
        if (! $customer || empty($customer->device_token)) {
            return false;
        }

        return true;
    }

    /**
     * Send customer note notification to customer
     */
    private function sendNoteNotification(CustomerNote $customerNote, $customer): void
    {
        $notificationData = [
            'title' => __('adminTheme::app.notifications.push-notifications.customer-note.title'),
            'body'  => __('adminTheme::app.notifications.push-notifications.customer-note.body', [
                'note' => $this->truncateNote($customerNote->note),
            ]),
        ];

        $fieldData = [
            'type'         => 'customer_note',
            'customer_id'  => (string) $customer->id,
            'note_id'      => (string) $customerNote->id,
            'note'         => $customerNote->note,
            'title'        => $notificationData['title'],
            'body'         => $notificationData['body'],
        ];

        $this->sendNotificationToCustomer($fieldData, $notificationData, $customer->device_token);
    }

    /**
     * Check if customer note notifications are enabled
     */
    private function areNotificationsEnabled(): bool
    {
        return (bool) core()->getConfigData('general.api.notification_settings.enable_customer_note_notifications', false);
    }

    /**
     * Check if customer should be notified based on customer_notified field
     */
    private function shouldNotifyCustomer(CustomerNote $customerNote): bool
    {
        // Check if customer_notified field is set to 1
        if (! isset($customerNote->customer_notified) || $customerNote->customer_notified != 1) {
            return false;
        }

        return true;
    }

    /**
     * Truncate note for notification display
     */
    private function truncateNote(string $note, int $maxLength = 100): string
    {
        if (strlen($note) <= $maxLength) {
            return $note;
        }

        return substr($note, 0, $maxLength) . '...';
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
