<?php

namespace Webkul\AdminTheme\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FirebaseHelper
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
     * Check if Firebase is properly configured with caching
     */
    public static function isConfigured(): bool
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
     * Get Firebase project ID
     */
    public static function getProjectId(): ?string
    {
        if (! self::isConfigured()) {
            return null;
        }

        return self::$firebaseConfig['project_id'] ?? null;
    }

    /**
     * Send Firebase message with cURL (optimized)
     */
    public static function sendMessage(array $message, array $fieldData = []): bool
    {
        $projectId = self::getProjectId();

        if (! $projectId) {
            Log::warning('Firebase project ID not found');

            return false;
        }

        $accessToken = self::getAccessToken();
        if (! $accessToken) {
            Log::error('Failed to get Firebase access token');

            return false;
        }

        $headers = [
            'Content-Type: application/json',
            "Authorization: Bearer {$accessToken}",
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_POSTFIELDS     => json_encode(['message' => $message]),
            CURLOPT_TIMEOUT        => 15, // Reduced timeout for faster response
            CURLOPT_CONNECTTIMEOUT => 5,  // Faster connection timeout
        ]);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($result === false || $curlError) {
            Log::error('cURL error sending notification', [
                'field_data' => $fieldData,
                'error'      => $curlError,
            ]);

            return false;
        }

        $response = json_decode($result, true);

        if ($httpCode === 200 && isset($response['name'])) {
            Log::info(' Notification sent successfully', [
                'field_data'  => $fieldData,
                'message_id'  => $response['name'],
            ]);

            return true;
        } else {
            // Handle specific Firebase errors
            $errorCode = $response['error']['errorCode'] ?? null;
            $errorMessage = $response['error']['message'] ?? 'Unknown error';

            if ($errorCode === 'UNREGISTERED') {
                Log::warning(' Device token is invalid or expired', [
                    'field_data'    => $fieldData,
                    'error_code'    => $errorCode,
                    'error_message' => $errorMessage,
                ]);

                // Clean up invalid token if it's a device token
                if (isset($message['token'])) {
                    self::cleanupInvalidTokens([$message['token']]);
                }
            } else {
                Log::warning('Notification response unexpected', [
                    'field_data' => $fieldData,
                    'http_code'  => $httpCode,
                    'response'   => $response,
                ]);
            }

            return false;
        }
    }

    /**
     * Send notification to specific device token
     */
    public static function sendToDevice(string $deviceToken, array $notification, array $data = []): bool
    {
        // Validate device token format
        if (! self::isValidDeviceToken($deviceToken)) {
            Log::warning(' Invalid device token format', [
                'device_token' => substr($deviceToken, 0, 20).'...',
                'field_data'   => $data,
            ]);

            return false;
        }

        $message = [
            'token'        => $deviceToken,
            'data'         => $data,
            'notification' => $notification,
        ];

        return self::sendMessage($message, $data);
    }

    /**
     * Validate device token format
     */
    private static function isValidDeviceToken(string $token): bool
    {
        // Firebase FCM tokens are typically 163 characters long
        // and contain alphanumeric characters, colons, and hyphens
        if (strlen($token) < 100 || strlen($token) > 200) {
            return false;
        }

        // Check if token contains valid characters
        return preg_match('/^[a-zA-Z0-9:_-]+$/', $token) === 1;
    }

    /**
     * Clean up invalid device tokens from database
     * Call this when you get UNREGISTERED error
     */
    public static function cleanupInvalidTokens(array $invalidTokens): void
    {
        if (empty($invalidTokens)) {
            return;
        }

        try {
            // Update customer device tokens to null
            \DB::table('customers')
                ->whereIn('device_token', $invalidTokens)
                ->update(['device_token' => null]);

            Log::info(' Cleaned up invalid device tokens', [
                'count'  => count($invalidTokens),
                'tokens' => array_map(function ($token) {
                    return substr($token, 0, 20).'...';
                }, $invalidTokens),
            ]);
        } catch (\Exception $e) {
            Log::error(' Failed to cleanup invalid tokens', [
                'error'  => $e->getMessage(),
                'tokens' => $invalidTokens,
            ]);
        }
    }

    /**
     * Send notification to topic
     */
    public static function sendToTopic(string $topic, array $notification, array $data = []): bool
    {
        $message = [
            'topic'        => $topic,
            'data'         => $data,
            'notification' => $notification,
        ];

        return self::sendMessage($message, $data);
    }

    /**
     * Generate Firebase access token (with caching)
     */
    private static function getAccessToken(): ?string
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

            $base64UrlHeader = self::base64UrlEncode($header);
            $base64UrlPayload = self::base64UrlEncode($payload);

            openssl_sign("{$base64UrlHeader}.{$base64UrlPayload}", $signature, $privateKey, OPENSSL_ALGO_SHA256);
            $base64UrlSignature = self::base64UrlEncode($signature);
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
            Log::error('Failed to generate Firebase access token', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Encode a string into a base64 URL-safe format
     */
    private static function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
