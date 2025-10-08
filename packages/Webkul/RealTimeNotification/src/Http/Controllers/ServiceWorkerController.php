<?php

namespace Webkul\RealTimeNotification\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServiceWorkerController
{
    /**
     * Generate dynamic Firebase Messaging Service Worker
     */
    public function generateServiceWorker()
    {
        $firebaseConfig = firebase_helper()->getFirebaseConfig();
        
        $serviceWorkerContent = $this->getServiceWorkerTemplate($firebaseConfig);
        
        return response($serviceWorkerContent)
            ->header('Content-Type', 'application/javascript')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Get Service Worker template with dynamic configuration
     */
    private function getServiceWorkerTemplate($firebaseConfig)
    {
        return "
// Firebase Cloud Messaging Service Worker
// Generated dynamically by Bagisto RealTimeNotification

// Import Firebase scripts
importScripts('https://www.gstatic.com/firebasejs/10.14.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.14.1/firebase-messaging-compat.js');

// Firebase configuration
const firebaseConfig = {
    apiKey: '{$firebaseConfig['apiKey']}',
    authDomain: '{$firebaseConfig['authDomain']}',
    projectId: '{$firebaseConfig['projectId']}',
    storageBucket: '{$firebaseConfig['storageBucket']}',
    messagingSenderId: '{$firebaseConfig['messagingSenderId']}',
    appId: '{$firebaseConfig['appId']}',
    measurementId: '{$firebaseConfig['measurementId']}'
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Initialize Firebase Cloud Messaging
const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log('📩 Background message received:', payload);

    const notificationTitle = payload.notification?.title || 'إشعار جديد';
    const notificationOptions = {
        body: payload.notification?.body || 'لديك إشعار جديد',
        icon: payload.notification?.icon || '/favicon.ico',
        badge: payload.notification?.badge || '/favicon.ico',
        tag: payload.data?.tag || 'bagisto-notification',
        data: payload.data || {},
        actions: [
            {
                action: 'view',
                title: 'عرض',
                icon: '/icon-192x192.png'
            },
            {
                action: 'dismiss',
                title: 'إغلاق',
                icon: '/icon-192x192.png'
            }
        ],
        requireInteraction: true,
        silent: false
    };

    // Show notification
    self.registration.showNotification(notificationTitle, notificationOptions);
});

// Handle notification click
self.addEventListener('notificationclick', (event) => {
    console.log('🔔 Notification clicked:', event);

    event.notification.close();

    if (event.action === 'view') {
        // Open the app or specific page
        const urlToOpen = event.notification.data?.click_action || '/';
        event.waitUntil(
            clients.openWindow(urlToOpen)
        );
    } else if (event.action === 'dismiss') {
        // Just close the notification
        console.log('Notification dismissed');
    } else {
        // Default action - open the app
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});

// Handle notification close
self.addEventListener('notificationclose', (event) => {
    console.log('🔕 Notification closed:', event);
});

// Handle push events
self.addEventListener('push', (event) => {
    console.log('📤 Push event received:', event);

    if (event.data) {
        const data = event.data.json();
        console.log('Push data:', data);
    }
});

// Handle service worker installation
self.addEventListener('install', (event) => {
    console.log('🔧 Service Worker installing...');
    self.skipWaiting();
});

// Handle service worker activation
self.addEventListener('activate', (event) => {
    console.log('✅ Service Worker activated');
    event.waitUntil(self.clients.claim());
});

// Handle service worker errors
self.addEventListener('error', (event) => {
    console.error('❌ Service Worker error:', event);
});

// Handle service worker unhandled promise rejections
self.addEventListener('unhandledrejection', (event) => {
    console.error('❌ Service Worker unhandled rejection:', event);
});

console.log('🚀 Firebase Messaging Service Worker loaded successfully');
        ";
    }
}
