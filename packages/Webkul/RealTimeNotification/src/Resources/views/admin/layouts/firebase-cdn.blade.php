@if(!empty(core()->getConfigData('general.firebase.settings.api_key')))
<!-- Firebase CDN - بدون بناء -->
<script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-messaging-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-analytics-compat.js"></script>

<script>
    // تعريف Firebase بدون بناء
    const firebaseConfig = {
        apiKey: "AIzaSyB4WUgl9IyTd7dm4cDy_OH-OUX6x7ZObgE",
        authDomain: "najaz-store.firebaseapp.com",
        projectId: "najaz-store",
        storageBucket: "najaz-store.firebasestorage.app",
        messagingSenderId: "565715806307",
        appId: "1:565715806307:web:d9ebd4f473ec3ef4623056",
        measurementId: "G-H75464RFFL"
    };

    // تهيئة Firebase - تطبيق افتراضي أولاً (مع فحص التكرار)
    let firebaseApp, analytics, messaging;

    try {
        // فحص إذا كان Firebase مُهيأ بالفعل
        if (firebase.apps.length === 0) {
            firebaseApp = firebase.initializeApp(firebaseConfig);
        } else {
            firebaseApp = firebase.app();
        }

        // تهيئة Analytics
        analytics = firebase.analytics();

        // تهيئة Messaging
        messaging = firebase.messaging();

        console.log('Firebase Shop initialized successfully');
    } catch (error) {
        console.error('Firebase Shop initialization error:', error);
    }

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
            // فحص إذا كان messaging متاح
            if (!messaging) {
                console.log('Messaging not available');
                return;
            }

            const permission = await Notification.requestPermission();

            if (permission === 'granted') {
                console.log('Notification permission granted.');

                try {
                    // الحصول على FCM token
                    const token = await messaging.getToken();

                    if (token) {
                        console.log('FCM Token:', token);
                        // إرسال الـ token إلى الخادم
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
                    } else {
                        console.log('No FCM token available');
                    }
                } catch (tokenError) {
                    console.error('Error getting FCM token:', tokenError);
                }
            } else {
                console.log('Notification permission denied');
            }
        } catch (error) {
            console.error('Error requesting permission:', error);
        }
    }

    // التعامل مع الرسائل في المقدمة
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

        // إرسال حدث مخصص للصفحة (للتسجيل فقط)
        window.dispatchEvent(new CustomEvent('firebase-notification', {
            detail: { title, body }
        }));
    });

    // تهيئة عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', () => {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js')
                .then((registration) => {
                    console.log('Service Worker registered:', registration);
                    // لا نطلب إذن الإشعارات تلقائياً لتجنب التحذير
                    console.log('Service Worker ready - call requestNotificationPermission() manually');
                })
                .catch((error) => {
                    console.log('Service Worker registration failed:', error);
                });
        }
    });

    // وظائف عامة للاستخدام
    window.firebaseNotification = {
        show: showNotification,
        requestPermission: requestNotificationPermission,
        messaging: messaging,
        analytics: analytics
    };
</script>
@endif
