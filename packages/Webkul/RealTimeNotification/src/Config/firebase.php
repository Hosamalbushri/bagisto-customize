<?php

// إعدادات Firebase المؤقتة
// يمكنك الحصول على VAPID key من Firebase Console > Project Settings > Cloud Messaging > Web Push certificates

return [
    'firebase' => [
        'settings' => [
            'api_key' => 'AIzaSyB4WUgl9IyTd7dm4cDy_OH-OUX6x7ZObgE',
            'auth_domain' => 'najaz-store.firebaseapp.com',
            'project_id' => 'najaz-store',
            'storage_bucket' => 'najaz-store.firebasestorage.app',
            'messaging_sender_id' => '565715806307',
            'app_id' => '1:565715806307:web:d9ebd4f473ec3ef4623056',
            'measurement_id' => 'G-H75464RFFL',
            'vapid_key' => '', // أضف VAPID key هنا من Firebase Console
        ],
    ],
    'notification' => [
        'settings' => [
            'enable_notifications' => true,
        ],
    ],
];