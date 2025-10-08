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
}
