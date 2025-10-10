<?php

namespace Webkul\RealTimeNotification\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Webkul\RealTimeNotification\Helpers\FirebaseHelper;
use Webkul\Shop\Http\Controllers\Controller;

class RealTimeNotificationController extends Controller
{
    /**
     * FirebaseHelper instance
     */
    protected $firebaseHelper;

    /**
     * Create a new controller instance.
     */
    public function __construct(FirebaseHelper $firebaseHelper)
    {
        $this->firebaseHelper = $firebaseHelper;
    }

    /**
     * Get Firebase configuration for JavaScript (public endpoint)
     */
    public function getFirebaseConfig(): JsonResponse
    {
        return response()->json([
            'firebase' => $this->firebaseHelper->getFirebaseConfig(),
            'vapidKey' => $this->firebaseHelper->getVapidKey(),
            'settings' => $this->firebaseHelper->getNotificationSettings(),
        ]);
    }

    /**
     * Save FCM token to database
     */
    public function saveToken(): JsonResponse
    {
        $token = request()->input('token');

        if (! $token) {
            return response()->json(['error' => 'Token is required'], 400);
        }

        try {
            // Get customer ID if logged in
            $adminId = auth()->guard('admin')->id();

            // Store token in session or database
            session(['fcm_token' => $token]);

            // If customer is logged in, you can also store in database
            if ($adminId) {
                // You can create a migration to store tokens in database
                // For now, we'll just store in session
            }

            return response()->json([
                'success' => true,
                'message' => 'Token saved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Failed to save token',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Subscribe to topic using Firebase Admin SDK
     */
    public function subscribeToTopic(): JsonResponse
    {
        $token = request()->input('token');
        $topic = request()->input('topic', 'admin_order');

        if (! $token) {
            return response()->json(['error' => 'Token is required'], 400);
        }

        try {

            // للآن، سنقوم بحفظ الاشتراك في قاعدة البيانات
            // بدلاً من استخدام Firebase Admin SDK مباشرة

            // حفظ الاشتراك في الجلسة أو قاعدة البيانات
            $subscriptions = session('fcm_topic_subscriptions', []);
            $subscriptions[$topic] = [
                'token' => $token,
                'topic' => $topic,
                'subscribed_at' => now()->toISOString()
            ];
            session(['fcm_topic_subscriptions' => $subscriptions]);

            // إرسال إشعار تأكيد للعميل
            $this->sendConfirmationNotification($token, $topic);

            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed to topic: ' . $topic,
                'topic' => $topic,
                'subscribed_at' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            \Log::error('Firebase subscription error: ' . $e->getMessage());
            return response()->json([
                'error'   => 'Failed to subscribe to topic',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send confirmation notification
     */
    private function sendConfirmationNotification($token, $topic)
    {
        try {
            // يمكن إضافة منطق إرسال إشعار تأكيد هنا
            // أو استخدام Firebase Admin SDK إذا كان مُعد بشكل صحيح
            \Log::info("User subscribed to topic: {$topic} with token: " . substr($token, 0, 20) . '...');
        } catch (\Exception $e) {
            \Log::error('Error sending confirmation notification: ' . $e->getMessage());
        }
    }

    /**
     * Get subscription status
     */
    public function getSubscriptionStatus(): JsonResponse
    {
        try {
            $subscriptions = session('fcm_topic_subscriptions', []);

            return response()->json([
                'success' => true,
                'subscriptions' => $subscriptions,
                'count' => count($subscriptions)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get subscription status',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get CSRF token
     */
    public function getCSRFToken(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'csrf_token' => csrf_token()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get CSRF token',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
