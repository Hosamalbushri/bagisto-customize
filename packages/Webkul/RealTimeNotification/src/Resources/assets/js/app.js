/**
 * Firebase Real-Time Notification App
 * إعدادات Firebase والإشعارات
 */

class FirebaseNotificationApp {
    constructor() {
        this.firebaseApp = null;
        this.analytics = null;
        this.messaging = null;
        this.config = null;
        this.vapidKey = null;
        this.isInitialized = false;

        this.init();
    }

    /**
     * تهيئة التطبيق
     */
    async init() {
        try {
            // الحصول على الإعدادات من البيانات المدمجة
            this.config = window.firebaseConfig || null;
            this.vapidKey = window.firebaseVapidKey || null;

            if (!this.config) {
                console.warn('Firebase config not found');
                return;
            }

            await this.initializeFirebase();
            await this.setupServiceWorker();
            await this.setupMessaging();

            this.isInitialized = true;
            console.log('✅ Firebase Notification App initialized successfully');

        } catch (error) {
            console.error('❌ Firebase Notification App initialization error:', error);
        }
    }

    /**
     * تهيئة Firebase
     */
    async initializeFirebase() {
        try {
            // استخدام Firebase v9+ modular SDK
            const { initializeApp } = await import('firebase/app');
            const { getAnalytics } = await import('firebase/analytics');
            const { getMessaging } = await import('firebase/messaging');


            // تهيئة التطبيق
            this.firebaseApp = initializeApp(this.config);

            // تهيئة Analytics
            this.analytics = getAnalytics(this.firebaseApp);

            // تهيئة Messaging
            this.messaging = getMessaging(this.firebaseApp);


            console.log('✅ Firebase initialized successfully with v9+ modular SDK');
        } catch (error) {
            console.error('❌ Firebase initialization error:', error);
            throw error;
        }
    }

    /**
     * إعداد Service Worker
     */
    async setupServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register(window.serviceWorkerUrl);
                console.log('✅ Service Worker registered successfully:', registration);
            } catch (error) {
                console.error('❌ Service Worker registration failed:', error);
            }
        }
    }


    /**
     * إعداد الرسائل والإشعارات
     */
    async setupMessaging() {
        if (!this.messaging) {
            console.warn('Messaging not available');
            return;
        }

        // الحصول على FCM Token
        await this.getFCMToken();

        // إعداد معالج الرسائل
        this.setupMessageHandlers();
    }

    /**
     * الحصول على FCM Token
     */
    async getFCMToken() {
        try {
            const { getToken } = await import('firebase/messaging');

            const currentToken = await getToken(this.messaging, {
                vapidKey: this.vapidKey
            });

            if (currentToken) {
                console.log('🔑 FCM Token:', currentToken);
                // await this.saveTokenToServer(currentToken);
                await this.subscribeToTopic(currentToken);
            } else {
                console.log('❌ No FCM token available');
                await this.requestNotificationPermission();
            }
        } catch (error) {
            console.error('❌ Error getting FCM token:', error);
        }
    }

    // /**
    //  * حفظ Token في الخادم
    //  */
    // async saveTokenToServer(token) {
    //     try {
    //         const response = await fetch('/realtimenotification/save-token', {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //                 'X-CSRF-TOKEN': this.getCSRFToken()
    //             },
    //             body: JSON.stringify({ token: token })
    //         });
    //
    //         if (response.ok) {
    //             console.log('✅ Token saved successfully');
    //         } else {
    //             console.error('❌ Error saving token:', response.statusText);
    //         }
    //     } catch (error) {
    //         console.error('❌ Error saving token:', error);
    //     }
    // }

    /**
     * الاشتراك في Topic عبر الخادم
     */
    async subscribeToTopic(token) {
        try {
            // استخدام الخادم للاشتراك في المواضيع بدلاً من Firebase IID API المهمل
            const topic = 'general-notifications'; // يمكن تغيير هذا حسب الحاجة

            const response = await fetch('/realtimenotification/subscribe-topic', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify({
                    token: token,
                    topic: topic
                })
            });

            if (response.ok) {
                const result = await response.json();
                console.log('✅ Successfully subscribed to topic:', topic, result);
            } else {
                const errorText = await response.text();
                console.error('❌ Error subscribing to topic:', response.status, errorText);
            }

        } catch (error) {
            console.error('❌ Error subscribing to topic:', error);
            // لا نرمي الخطأ هنا لتجنب توقف التطبيق
            console.warn('⚠️ Topic subscription failed, but app will continue to work');
        }
    }

    /**
     * طلب إذن الإشعارات
     */
    async requestNotificationPermission() {
        try {
            if (!this.messaging) {
                console.log('Messaging not available');
                return;
            }

            const permission = await Notification.requestPermission();

            if (permission === 'granted') {
                console.log('✅ Notification permission granted');
                await this.getFCMToken();
            } else {
                console.log('❌ Notification permission denied');
            }
        } catch (error) {
            console.error('❌ Error requesting permission:', error);
        }
    }

    /**
     * إعداد معالجات الرسائل
     */
    async setupMessageHandlers() {
        try {
            const { onMessage } = await import('firebase/messaging');

            // التعامل مع الرسائل في المقدمة
            onMessage(this.messaging, (payload) => {
                console.log('📨 Message received:', payload);
                this.showNotification(
                    payload.notification?.title || 'إشعار جديد',
                    payload.notification?.body || 'لديك إشعار جديد'
                );
            });
        } catch (error) {
            console.error('❌ Error setting up message handlers:', error);
        }
    }

    /**
     * عرض الإشعار
     */
    showNotification(title, body) {
        const notification = document.createElement('div');
        notification.className = 'firebase-notification success';
        notification.innerHTML = `
            <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
            <div class="title">${title}</div>
            <div class="message">${body}</div>
        `;

        document.body.appendChild(notification);

        // إزالة الإشعار تلقائياً بعد 5 ثوان
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    /**
     * الحصول على CSRF Token
     */
    getCSRFToken() {
        // محاولة الحصول من meta tag
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta && meta.getAttribute('content')) {
            return meta.getAttribute('content');
        }

        // محاولة الحصول من input hidden
        const csrfInput = document.querySelector('input[name="_token"]');
        if (csrfInput && csrfInput.value) {
            return csrfInput.value;
        }

        // محاولة الحصول من window object
        if (window.Laravel && window.Laravel.csrfToken) {
            return window.Laravel.csrfToken;
        }

        // محاولة الحصول من document.cookie
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === 'XSRF-TOKEN') {
                return decodeURIComponent(value);
            }
        }

        console.warn('⚠️ CSRF token not found. Please ensure CSRF token is available.');
        return '';
    }

    /**
     * الحصول على CSRF Token من الخادم
     */
    async fetchCSRFToken() {
        try {
            const response = await fetch('/realtimenotification/csrf-token', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                return data.csrf_token || '';
            }
        } catch (error) {
            console.error('❌ Error fetching CSRF token:', error);
        }
        return '';
    }

    /**
     * إرسال إشعار مخصص
     */
    async sendCustomNotification(title, body, data = {}) {
        try {
            // يمكن إضافة منطق إرسال إشعار مخصص هنا
            this.showNotification(title, body);
        } catch (error) {
            console.error('❌ Error sending custom notification:', error);
        }
    }

    /**
     * الحصول على حالة التطبيق
     */
    getStatus() {
        return {
            isInitialized: this.isInitialized,
            hasFirebase: !!this.firebaseApp,
            hasMessaging: !!this.messaging,
            hasAnalytics: !!this.analytics
        };
    }

    /**
     * التحقق من حالة الاشتراك
     */
    async getSubscriptionStatus() {
        try {
            const response = await fetch('/realtimenotification/subscription-status', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            if (response.ok) {
                const result = await response.json();
                console.log('📊 Subscription status:', result);
                return result;
            } else {
                console.error('❌ Error getting subscription status:', response.statusText);
                return null;
            }
        } catch (error) {
            console.error('❌ Error getting subscription status:', error);
            return null;
        }
    }
}

// تهيئة التطبيق عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // التحقق من وجود Firebase config
    if (window.firebaseConfig) {
        window.firebaseNotificationApp = new FirebaseNotificationApp();

        // تصدير الوظائف للاستخدام العام
        window.firebaseNotification = {
            show: (title, body) => window.firebaseNotificationApp.showNotification(title, body),
            requestPermission: () => window.firebaseNotificationApp.requestNotificationPermission(),
            sendCustom: (title, body, data) => window.firebaseNotificationApp.sendCustomNotification(title, body, data),
            getStatus: () => window.firebaseNotificationApp.getStatus(),
            getSubscriptionStatus: () => window.firebaseNotificationApp.getSubscriptionStatus(),
            messaging: () => window.firebaseNotificationApp.messaging,
            analytics: () => window.firebaseNotificationApp.analytics,
            firebaseApp: () => window.firebaseNotificationApp.firebaseApp
        };
    } else {
        console.warn('Firebase config not found. Please ensure firebaseConfig is available in window object.');
    }
});

// تصدير الكلاس للاستخدام في الوحدات الأخرى
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FirebaseNotificationApp;
}
