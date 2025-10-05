@php
    $firebaseConfig = firebase_helper()->getFirebaseConfig();
    $notificationsEnabled = firebase_helper()->isNotificationEnabled();
@endphp

@if(!empty($firebaseConfig['apiKey']) && !empty($firebaseConfig['projectId']) && !empty($firebaseConfig['messagingSenderId']))

<script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-messaging-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-analytics-compat.js"></script>

<script>
    // تعريف Firebase بدون بناء
    const firebaseConfig = {
        apiKey: "{{$firebaseConfig['apiKey']}}",
        authDomain: "{{$firebaseConfig['authDomain']}}",
        projectId: "{{$firebaseConfig['projectId']}}",
        storageBucket: "{{$firebaseConfig['storageBucket']}}",
        messagingSenderId: "{{$firebaseConfig['messagingSenderId']}}",
        appId: "{{$firebaseConfig['appId']}}",
        measurementId: "{{$firebaseConfig['measurementId']}}"
    };

    let firebaseApp, analytics, messaging;

    try {
        if (firebase.apps.length === 0) {
            firebaseApp = firebase.initializeApp(firebaseConfig);
        } else {
            firebaseApp = firebase.app();
        }
        analytics = firebase.analytics();

        messaging = firebase.messaging();

        console.log('Firebase initialized successfully for Admin Panel');
    } catch (error) {
        console.error('Firebase Shop initialization error:', error);
    }

    async function requestNotificationPermission() {
        try {
            if (!messaging) {
                console.log('Messaging not available');
                return;
            }
            const permission = await Notification.requestPermission();
        } catch (error) {
            console.error('❌ Permission request error:', error);
        }
    }
    messaging.onMessage((payload) => {
        console.log('📩 New message received:', payload);

        // 🔥 أطلق حدث مخصص في الصفحة
        const event = new CustomEvent('firebase-notification-received', { detail: payload });
        window.dispatchEvent(event);

        // 🔔 مثلاً أظهر إشعار في الصفحة (اختياري)
        if (Notification.permission === 'granted') {
            new Notification(payload.notification.title, {
                body: payload.notification.body,
                icon: payload.notification.icon,
            });
        }
    });
    window.firebaseNotification = {
        requestPermission: requestNotificationPermission,
        messaging: messaging,
        analytics: analytics
    };

</script>
@endif
