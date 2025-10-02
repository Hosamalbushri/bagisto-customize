@extends('shop::layouts.master')

@section('page_title')
    {{ __('realtimenotification::app.shop.notifications.title') }}
@endsection

@section('head')
    @parent
    <!-- Firebase CDN -->
    <script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-messaging-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-analytics-compat.js"></script>
@endsection

@section('content-wrapper')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('realtimenotification::app.shop.notifications.title') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h5>مرحباً بك في صفحة الإشعارات!</h5>
                            <p>هذه الصفحة تختبر إشعارات Firebase. ستظهر الإشعارات هنا عند وصولها.</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h5>حالة الإشعارات:</h5>
                                <div id="notification-status">
                                    <span class="badge badge-secondary">جاري التحميل...</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>اختبار الإشعارات:</h5>
                                <button id="test-notification" class="btn btn-primary">
                                    إرسال إشعار تجريبي
                                </button>
                                <button id="request-permission" class="btn btn-success">
                                    طلب إذن الإشعارات
                                </button>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h5>الإشعارات الواردة:</h5>
                            <div id="notifications-list" class="list-group">
                                <!-- الإشعارات ستظهر هنا -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // تعريف Firebase باستخدام CDN
        const firebaseConfig = {
            apiKey: "AIzaSyB4WUgl9IyTd7dm4cDy_OH-OUX6x7ZObgE",
            authDomain: "najaz-store.firebaseapp.com",
            projectId: "najaz-store",
            storageBucket: "najaz-store.firebasestorage.app",
            messagingSenderId: "565715806307",
            appId: "1:565715806307:web:d9ebd4f473ec3ef4623056",
            measurementId: "G-H75464RFFL"
        };

        // تهيئة Firebase - تطبيق افتراضي أولاً
        firebase.initializeApp(firebaseConfig);
        const analytics = firebase.analytics();
        const messaging = firebase.messaging();

        // وظيفة عرض الإشعارات
        function showNotification(title, body) {
            const notification = document.createElement('div');
            notification.className = 'firebase-notification success';
            notification.innerHTML = `
                <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
                <div class="title">${title}</div>
                <div class="message">${body}</div>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        // طلب إذن الإشعارات
        async function requestNotificationPermission() {
            try {
                const permission = await Notification.requestPermission();
                
                if (permission === 'granted') {
                    console.log('Notification permission granted.');
                    
                    const token = await messaging.getToken();
                    
                    if (token) {
                        console.log('FCM Token:', token);
                        
                        try {
                            await fetch('/realtimenotification/save-token', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                },
                                body: JSON.stringify({ token: token })
                            });
                            console.log('Token saved successfully');
                        } catch (error) {
                            console.error('Error saving token:', error);
                        }
                    }
                } else {
                    console.log('Notification permission denied');
                }
            } catch (error) {
                console.error('Error requesting permission:', error);
            }
        }

        // التعامل مع الرسائل
        messaging.onMessage((payload) => {
            console.log('Message received:', payload);
            
            const title = payload.notification?.title || 'إشعار جديد';
            const body = payload.notification?.body || 'لديك إشعار جديد';
            
            // استخدام الإشعار الافتراضي للمتصفح فقط
            if (Notification.permission === 'granted') {
                new Notification(title, {
                    body: body,
                    icon: '/favicon.ico',
                    badge: '/favicon.ico',
                    tag: 'firebase-notification',
                    requireInteraction: true
                });
            }
            
            // إرسال حدث مخصص للتسجيل في الصفحة
            window.dispatchEvent(new CustomEvent('firebase-notification', {
                detail: { title, body }
            }));
        });

        document.addEventListener('DOMContentLoaded', function() {
            const statusElement = document.getElementById('notification-status');
            const testButton = document.getElementById('test-notification');
            const requestButton = document.getElementById('request-permission');
            const notificationsList = document.getElementById('notifications-list');

            // Check notification permission status
            function updateNotificationStatus() {
                if ('Notification' in window) {
                    if (Notification.permission === 'granted') {
                        statusElement.innerHTML = '<span class="badge badge-success">مفعل</span>';
                    } else if (Notification.permission === 'denied') {
                        statusElement.innerHTML = '<span class="badge badge-danger">مرفوض</span>';
                    } else {
                        statusElement.innerHTML = '<span class="badge badge-warning">غير محدد</span>';
                    }
                } else {
                    statusElement.innerHTML = '<span class="badge badge-danger">غير مدعوم</span>';
                }
            }

            // Test notification function
            function showTestNotification() {
                if (Notification.permission === 'granted') {
                    new Notification('إشعار تجريبي', {
                        body: 'هذا إشعار تجريبي لاختبار النظام',
                        icon: '/favicon.ico',
                        badge: '/favicon.ico',
                        tag: 'test-notification',
                        requireInteraction: true
                    });
                } else {
                    alert('يرجى طلب إذن الإشعارات أولاً');
                }
            }

            // Add notification to list
            function addNotificationToList(title, body, timestamp = new Date()) {
                const notificationItem = document.createElement('div');
                notificationItem.className = 'list-group-item';
                notificationItem.innerHTML = `
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${title}</h6>
                        <small>${timestamp.toLocaleTimeString('ar-SA')}</small>
                    </div>
                    <p class="mb-1">${body}</p>
                `;
                notificationsList.insertBefore(notificationItem, notificationsList.firstChild);
            }

            // Event listeners
            testButton.addEventListener('click', showTestNotification);
            requestButton.addEventListener('click', requestNotificationPermission);

            // Update status on load
            updateNotificationStatus();

            // Listen for custom notification events
            window.addEventListener('firebase-notification', function(event) {
                const { title, body } = event.detail;
                addNotificationToList(title, body);
            });

            // تهيئة Service Worker
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/firebase-messaging-sw.js')
                    .then((registration) => {
                        console.log('Service Worker registered:', registration);
                        // لا نطلب إذن الإشعارات تلقائياً لتجنب التحذير
                        console.log('Service Worker ready - user can request permission manually');
                    })
                    .catch((error) => {
                        console.log('Service Worker registration failed:', error);
                    });
            }

            console.log('صفحة الإشعارات جاهزة!');
        });
    </script>
@endsection
