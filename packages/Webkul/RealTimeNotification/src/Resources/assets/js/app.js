import.meta.glob(["../images/**"]);

// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import { getMessaging, getToken, onMessage } from "firebase/messaging";

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const najazFirebaseConfig = {
  apiKey: "AIzaSyB4WUgl9IyTd7dm4cDy_OH-OUX6x7ZObgE",
  authDomain: "najaz-store.firebaseapp.com",
  projectId: "najaz-store",
  storageBucket: "najaz-store.firebasestorage.app",
  messagingSenderId: "565715806307",
  appId: "1:565715806307:web:d9ebd4f473ec3ef4623056",
  measurementId: "G-H75464RFFL"
};

// Initialize Firebase with a unique name to avoid conflicts
const najazFirebaseApp = initializeApp(najazFirebaseConfig, 'najaz-store-app');
const najazAnalytics = getAnalytics(najazFirebaseApp);
const najazMessaging = getMessaging(najazFirebaseApp);

// Notification display function
function showNotification(title, body, icon = '/favicon.ico') {
  // Create notification element
  const notification = document.createElement('div');
  notification.className = 'firebase-notification success';
  notification.innerHTML = `
    <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
    <div class="title">${title}</div>
    <div class="message">${body}</div>
  `;

  // Add to page
  document.body.appendChild(notification);

  // Auto remove after 5 seconds
  setTimeout(() => {
    if (notification.parentElement) {
      notification.remove();
    }
  }, 5000);
}

// Request notification permission and get token
async function requestNotificationPermission() {
  try {
    const permission = await Notification.requestPermission();

    if (permission === 'granted') {
      console.log('Notification permission granted.');

      // Get Firebase config including VAPID key
      const response = await fetch('/realtimenotification/firebase-config');
      const config = await response.json();

      // Get FCM token with VAPID key (optional)
      let tokenOptions = {};
      if (config.vapidKey && config.vapidKey !== 'YOUR_VAPID_KEY_HERE') {
        tokenOptions.vapidKey = config.vapidKey;
      }

      const token = await getToken(najazMessaging, tokenOptions);

      if (token) {
        console.log('FCM Token:', token);
        // Send token to server
        await sendTokenToServer(token);
      } else {
        console.log('No registration token available.');
      }
    } else {
      console.log('Unable to get permission to notify.');
    }
  } catch (error) {
    console.error('An error occurred while retrieving token:', error);
  }
}

// Send token to server
async function sendTokenToServer(token) {
  try {
    const response = await fetch('/realtimenotification/save-token', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
      },
      body: JSON.stringify({ token: token })
    });

    if (response.ok) {
      console.log('Token saved to server successfully');
    }
  } catch (error) {
    console.error('Error saving token to server:', error);
  }
}

// Handle foreground messages
onMessage(najazMessaging, (payload) => {
  console.log('Message received in foreground:', payload);

  const title = payload.notification?.title || 'إشعار جديد';
  const body = payload.notification?.body || 'لديك إشعار جديد';

  // Show notification in the page
  showNotification(title, body);

  // Dispatch custom event for the notification page
  window.dispatchEvent(new CustomEvent('firebase-notification', {
    detail: { title, body }
  }));

  // Also show browser notification if permission is granted
  if (Notification.permission === 'granted') {
    new Notification(title, {
      body: body,
      icon: '/favicon.ico'
    });
  }
});

// Initialize notifications when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  // Check if service worker is supported
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/firebase-messaging-sw.js')
      .then((registration) => {
        console.log('Service Worker registered successfully:', registration);
        // Request notification permission after service worker is ready
        requestNotificationPermission();
      })
      .catch((error) => {
        console.log('Service Worker registration failed:', error);
      });
  }
});

// Export for use in other modules if needed
export { najazFirebaseApp, najazAnalytics, najazMessaging, showNotification };
