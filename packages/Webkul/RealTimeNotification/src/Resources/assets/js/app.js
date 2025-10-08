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
            if (firebase.apps.length === 0) {
                this.firebaseApp = firebase.initializeApp(this.config);
            } else {
                this.firebaseApp = firebase.app();
            }
            
            this.analytics = firebase.analytics();
            this.messaging = firebase.messaging();
            
            console.log('✅ Firebase initialized successfully');
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
            const currentToken = await this.messaging.getToken({ 
                vapidKey: this.vapidKey 
            });

            if (currentToken) {
                console.log('🔑 FCM Token:', currentToken);
                await this.saveTokenToServer(currentToken);
                await this.subscribeToTopic(currentToken);
            } else {
                console.log('❌ No FCM token available');
                await this.requestNotificationPermission();
            }
        } catch (error) {
            console.error('❌ Error getting FCM token:', error);
        }
    }

    /**
     * حفظ Token في الخادم
     */
    async saveTokenToServer(token) {
        try {
            const response = await fetch('/realtimenotification/save-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify({ token: token })
            });

            if (response.ok) {
                console.log('✅ Token saved successfully');
            } else {
                console.error('❌ Error saving token:', response.statusText);
            }
        } catch (error) {
            console.error('❌ Error saving token:', error);
        }
    }

    /**
     * الاشتراك في Topic
     */
    async subscribeToTopic(token) {
        try {
            const topic = 'admin_order';
            const response = await firebase.subscribeToTopic([token], topic);
            console.log('✅ Successfully subscribed to topic:', response);
        } catch (error) {
            console.error('❌ Error subscribing to topic:', error);
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
    setupMessageHandlers() {
        // التعامل مع الرسائل في المقدمة
        this.messaging.onMessage((payload) => {
            console.log('📨 Message received:', payload);
            this.showNotification(
                payload.notification?.title || 'إشعار جديد',
                payload.notification?.body || 'لديك إشعار جديد'
            );
        });
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
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
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
}

// تهيئة التطبيق عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // التحقق من وجود Firebase
    if (typeof firebase !== 'undefined') {
        window.firebaseNotificationApp = new FirebaseNotificationApp();
        
        // تصدير الوظائف للاستخدام العام
        window.firebaseNotification = {
            show: (title, body) => window.firebaseNotificationApp.showNotification(title, body),
            requestPermission: () => window.firebaseNotificationApp.requestNotificationPermission(),
            sendCustom: (title, body, data) => window.firebaseNotificationApp.sendCustomNotification(title, body, data),
            getStatus: () => window.firebaseNotificationApp.getStatus(),
            messaging: () => window.firebaseNotificationApp.messaging,
            analytics: () => window.firebaseNotificationApp.analytics,
            firebaseApp: () => window.firebaseNotificationApp.firebaseApp
        };
    } else {
        console.warn('Firebase SDK not loaded');
    }
});

// تصدير الكلاس للاستخدام في الوحدات الأخرى
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FirebaseNotificationApp;
}