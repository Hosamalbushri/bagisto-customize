<?php

namespace Webkul\AdminTheme\Listeners\Sales;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Models\Order;

class OrderSaveListener
{
    /**
     * Firebase scope URL for authentication
     */
    private const SCOPE_URL = 'https://www.googleapis.com/auth/firebase.messaging';

    /**
     * Required Firebase configuration keys
     */
    private const REQUIRED_FIREBASE_KEYS = ['project_id', 'client_email', 'private_key', 'token_uri'];

    /**
     * Cached Firebase configuration
     */
    private static ?array $firebaseConfig = null;

    /**
     * Cached access token
     */
    private static ?string $cachedAccessToken = null;

    /**
     * Access token expiry time
     */
    private static ?int $tokenExpiry = null;

    /**
     * Handle the event when order is saved.
     */
    public function handle(Order $order): void
    {
        // Check if notifications are enabled
        if (! $this->areNotificationsEnabled()) {
            return;
        }

//        // Check Firebase configuration
//        if (! $this->isFirebaseConfigured()) {
//            Log::warning('Firebase configuration not found, skipping admin notification');
//            return;
//        }

        $this->sendAdminNotification($order);
    }

    /**
     * Check if order notifications are enabled
     */
    private function areNotificationsEnabled(): bool
    {
        return (bool) core()->getConfigData('general.firebase.notification.enable_notifications', false);
    }

    /**
     * Check if Firebase is properly configured with caching
     */
//    private function isFirebaseConfigured(): bool
//    {
//        // Use cached configuration if available
//        if (self::$firebaseConfig !== null) {
//            return ! empty(self::$firebaseConfig);
//        }
//
//        try {
//            $webProjectConfig = core()->getConfigData('general.firebase.settings.web_project_config');
//
//            if (! $webProjectConfig) {
//                self::$firebaseConfig = [];
//                return false;
//            }
//
//            $config = json_decode($webProjectConfig, true);
//
//            if (! $config) {
//                self::$firebaseConfig = [];
//                return false;
//            }
//
//            // Check for required keys
//            foreach (self::REQUIRED_FIREBASE_KEYS as $key) {
//                if (! isset($config[$key]) || empty($config[$key])) {
//                    self::$firebaseConfig = [];
//                    return false;
//                }
//            }
//
//            self::$firebaseConfig = $config;
//            return true;
//        } catch (\Exception $e) {
//            Log::error('Error checking Firebase configuration', [
//                'error' => $e->getMessage(),
//            ]);
//            self::$firebaseConfig = [];
//            return false;
//        }
//    }

    /**
     * Send admin notification for new order
     */
    private function sendAdminNotification(Order $order): void
    {
        $notificationData = [
            'title' => 'طلب جديد',
            'body'  => "تم إنشاء طلب جديد برقم: {$order->increment_id}",
        ];

        $fieldData = [
            'type'         => 'admin_order',
            'order_id'     => (string) $order->id,
            'order_number'  => $order->increment_id,
            'title'        => $notificationData['title'],
            'body'         => $notificationData['body'],
            'webpush'      => [
                'notification' => [
                    'title' => '💻 طلب جديد!',
                    'body'  => "تم إنشاء طلب جديد برقم: {$order->increment_id}",
                ]
            ],
        ];

        $this->sendNotificationToAdmin($fieldData, $notificationData);
    }

    /**
     * Send notification to admin using topic
     */
    private function sendNotificationToAdmin(array $fieldData, array $data): void
    {
        $projectId = self::$firebaseConfig['project_id'] ?? null;

        if (! $projectId) {
            return;
        }

        $accessToken = $this->getAccessToken();
        if (! $accessToken) {
            Log::error('Failed to get access token');
            return;
        }

        // Use admin topic for notifications
        $message = [
            'data'         => $fieldData,
            'notification' => [
                'body'  => $data['body'],
                'title' => $data['title'],
            ],
            'webpush'      => [
                'notification' => [
                    'title' => '💻 طلب جديد!',
                    'body'  => "تم إنشاء طلب جديد برقم: {$fieldData['order_number']}",
                ]
            ],
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
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($result === false || $curlError) {
            Log::error('cURL error');
            return;
        }

        $response = json_decode($result, true);

        if ($httpCode === 200 && isset($response['name'])) {
            Log::info('Notification sent');
        } else {
            Log::warning('Unexpected response');
        }
    }

    /**
     * Generate Firebase access token (with caching)
     */
    private function getAccessToken(): ?string
    {
        if (empty(self::$firebaseConfig)) {
            return null;
        }

        // Check if we have a valid cached token
        if (self::$cachedAccessToken && self::$tokenExpiry && time() < self::$tokenExpiry) {
            return self::$cachedAccessToken;
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
            $accessToken = $responseData['access_token'] ?? null;

            if ($accessToken) {
                // Cache the token with 50 minutes expiry (10 minutes buffer)
                self::$cachedAccessToken = $accessToken;
                self::$tokenExpiry = time() + 3000; // 50 minutes
            }

            return $accessToken;
        } catch (\Exception $e) {
            Log::error('Failed to generate access token');
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
