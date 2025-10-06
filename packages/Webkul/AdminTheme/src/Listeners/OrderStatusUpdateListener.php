<?php

namespace Webkul\AdminTheme\Listeners;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Models\Order;

class OrderStatusUpdateListener
{
    /**
     * Firebase scope URL for authentication
     */
    private const SCOPE_URL = 'https://www.googleapis.com/auth/firebase.messaging';

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

    private const STATUS_DELIVERED = 'delivered';

    /**
     * Statuses that should skip notifications
     */
    private const SKIP_NOTIFICATION_STATUSES = [
        self::STATUS_ACCEPTED_BY_AGENT,
        self::STATUS_REJECTED_BY_AGENT,
    ];

    /**
     * Required Firebase configuration keys
     */
    private const REQUIRED_FIREBASE_KEYS = ['project_id', 'client_email', 'private_key', 'token_uri'];

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
     * Cached Firebase configuration
     */
    private static ?array $firebaseConfig = null;

    /**
     * Handle the event when order status is updated.
     */
    public function handle(Order $order): void
    {
        // Early return for skipped statuses
        if (in_array($order->status, self::SKIP_NOTIFICATION_STATUSES, true)) {
            return;
        }

        // Early return if Firebase is not configured
        if (! $this->isFirebaseConfigured()) {
            Log::warning('Firebase configuration not found, skipping notification');
            return;
        }

        // Early return if customer is not available or has no device token
        $customer = $order->customer;
        if (! $customer || empty($customer->device_token)) {
            return;
        }

        $this->sendOrderNotification($order, $customer);
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
     * Check if Firebase is properly configured with caching
     */
    private function isFirebaseConfigured(): bool
    {
        // Use cached configuration if available
        if (self::$firebaseConfig !== null) {
            return ! empty(self::$firebaseConfig);
        }

        try {
            $privateKeyContent = core()->getConfigData('general.api.pushnotification.private_key');

            if (! $privateKeyContent) {
                self::$firebaseConfig = [];

                return false;
            }

            $config = json_decode($privateKeyContent, true);

            if (! $config) {
                self::$firebaseConfig = [];

                return false;
            }

            // Check for required keys
            foreach (self::REQUIRED_FIREBASE_KEYS as $key) {
                if (! isset($config[$key]) || empty($config[$key])) {
                    self::$firebaseConfig = [];

                    return false;
                }
            }

            self::$firebaseConfig = $config;

            return true;
        } catch (\Exception $e) {
            Log::error('Error checking Firebase configuration', [
                'error' => $e->getMessage(),
            ]);
            self::$firebaseConfig = [];

            return false;
        }
    }

    /**
     * Get translated status label
     */
    private function getStatusTranslation(string $status): string
    {
        $translationKey = self::STATUS_LABEL_KEYS[$status] ?? null;

        return $translationKey ? __($translationKey, [], $status) : $status;
    }

    /**
     * Send notification to customer device
     */
    private function sendNotificationToCustomer(array $fieldData, array $data, string $deviceToken): void
    {
        $projectId = self::$firebaseConfig['project_id'] ?? null;

        if (! $projectId) {
            return;
        }

        $accessToken = $this->getAccessToken();
        if (! $accessToken) {
            Log::error('Failed to get Firebase access token');

            return;
        }

        $message = [
            'data'         => $fieldData,
            'notification' => [
                'body'  => $data['body'],
                'title' => $data['title'],
            ],
            'topic' => 'Bagisto_mobikul',
        ];

        $headers = [
            'Content-Type: application/json',
            "Authorization: Bearer {$accessToken}",
        ];

        $this->sendFirebaseMessage($projectId, $message, $headers, $fieldData);
    }

    /**
     * Send Firebase message with cURL
     */
    private function sendFirebaseMessage(string $projectId, array $message, array $headers, array $fieldData): void
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_POSTFIELDS     => json_encode(['message' => $message]),
            CURLOPT_TIMEOUT        => 30,
        ]);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($result === false) {
            Log::error('❌ cURL error sending notification', [
                'order_id' => $fieldData['order_id'],
            ]);

            return;
        }

        $response = json_decode($result, true);

        if ($httpCode === 200 && isset($response['name'])) {
            Log::info('✅ Notification sent successfully', [
                'order_id'   => $fieldData['order_id'],
                'status'     => $fieldData['status'],
                'message_id' => $response['name'],
            ]);
        } else {
            Log::warning('⚠️ Notification response unexpected', [
                'order_id'  => $fieldData['order_id'],
                'http_code' => $httpCode,
                'response'  => $response,
            ]);
        }
    }

    /**
     * Generate Firebase access token
     */
    private function getAccessToken(): ?string
    {
        if (empty(self::$firebaseConfig)) {
            return null;
        }

        try {
            $privateKey = str_replace('\n', "\n", self::$firebaseConfig['private_key']);

            $header = json_encode(['typ' => 'JWT', 'alg' => 'RS256']);
            $payload = json_encode([
                'iss'   => self::$firebaseConfig['client_email'],
                'scope' => self::SCOPE_URL,
                'aud'   => self::$firebaseConfig['token_uri'],
                'exp'   => time() + 3600,
                'iat'   => time() - 60,
            ]);

            $base64UrlHeader = $this->base64UrlEncode($header);
            $base64UrlPayload = $this->base64UrlEncode($payload);

            openssl_sign("{$base64UrlHeader}.{$base64UrlPayload}", $signature, $privateKey, OPENSSL_ALGO_SHA256);
            $base64UrlSignature = $this->base64UrlEncode($signature);
            $jwt = "{$base64UrlHeader}.{$base64UrlPayload}.{$base64UrlSignature}";

            $client = new Client;
            $response = $client->post(self::$firebaseConfig['token_uri'], [
                'form_params' => [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion'  => $jwt,
                ],
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                'timeout' => 30,
            ]);

            $responseData = json_decode($response->getBody(), true);

            return $responseData['access_token'] ?? null;
        } catch (\Exception $e) {
            Log::error('Failed to generate Firebase access token', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Encode a string into a base64 URL-safe format
     */
    private function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
