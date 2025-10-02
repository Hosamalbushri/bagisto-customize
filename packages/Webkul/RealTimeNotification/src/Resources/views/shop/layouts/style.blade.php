{{--<script type="module">--}}
{{--  // Import the functions you need from the SDKs you need--}}
{{--  import { initializeApp } from "https://www.gstatic.com/firebasejs/12.3.0/firebase-app.js";--}}
{{--  import { getAnalytics } from "https://www.gstatic.com/firebasejs/12.3.0/firebase-analytics.js";--}}
{{--  import { getMessaging } from "https://www.gstatic.com/firebasejs/12.3.0/firebase-messaging.js";--}}
{{--  --}}
{{--  // Get Firebase configuration from server--}}
{{--  async function getFirebaseConfig() {--}}
{{--    try {--}}
{{--      const response = await fetch('/realtimenotification/firebase-config');--}}
{{--      const data = await response.json();--}}
{{--      return data.firebase;--}}
{{--    } catch (error) {--}}
{{--      console.error('Error fetching Firebase config:', error);--}}
{{--      // Fallback configuration--}}
{{--      return {--}}
{{--        apiKey: "AIzaSyB4WUgl9IyTd7dm4cDy_OH-OUX6x7ZObgE",--}}
{{--        authDomain: "najaz-store.firebaseapp.com",--}}
{{--        projectId: "najaz-store",--}}
{{--        storageBucket: "najaz-store.firebasestorage.app",--}}
{{--        messagingSenderId: "565715806307",--}}
{{--        appId: "1:565715806307:web:d9ebd4f473ec3ef4623056",--}}
{{--        measurementId: "G-H75464RFFL"--}}
{{--      };--}}
{{--    }--}}
{{--  }--}}

{{--  // Initialize Firebase--}}
{{--  async function initializeFirebase() {--}}
{{--    const firebaseConfig = await getFirebaseConfig();--}}
{{--    --}}
{{--    // Initialize Firebase App--}}
{{--    const app = initializeApp(firebaseConfig);--}}
{{--    --}}
{{--    // Initialize Analytics if measurementId is available--}}
{{--    let analytics = null;--}}
{{--    if (firebaseConfig.measurementId) {--}}
{{--      analytics = getAnalytics(app);--}}
{{--      console.log('Firebase Analytics initialized');--}}
{{--    }--}}
{{--    --}}
{{--    // Initialize Messaging--}}
{{--    let messaging = null;--}}
{{--    if ('serviceWorker' in navigator) {--}}
{{--      messaging = getMessaging(app);--}}
{{--      console.log('Firebase Messaging initialized');--}}
{{--    }--}}
{{--    --}}
{{--    // Make Firebase available globally--}}
{{--    window.firebaseApp = app;--}}
{{--    window.firebaseAnalytics = analytics;--}}
{{--    window.firebaseMessaging = messaging;--}}
{{--    --}}
{{--    console.log('Firebase initialized successfully');--}}
{{--  }--}}

{{--  // Initialize Firebase when DOM is loaded--}}
{{--  document.addEventListener('DOMContentLoaded', initializeFirebase);--}}
{{--</script>--}}
