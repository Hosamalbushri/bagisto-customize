<?php

return [
    'configuration' => [
        'firebase' => [
            'title' => 'Firebase Configuration',
            'info' => 'Configure Firebase settings for real-time notifications and analytics',
            'api_key' => 'API Key',
            'api_key_info' => 'Your Firebase API key (AIzaSy...)',
            'auth_domain' => 'Auth Domain',
            'auth_domain_info' => 'Your Firebase auth domain (e.g., project.firebaseapp.com)',
            'project_id' => 'Project ID',
            'project_id_info' => 'Your Firebase project ID',
            'storage_bucket' => 'Storage Bucket',
            'storage_bucket_info' => 'Your Firebase storage bucket (e.g., project.firebasestorage.app)',
            'messaging_sender_id' => 'Messaging Sender ID',
            'messaging_sender_id_info' => 'Your Firebase messaging sender ID (numeric)',
            'app_id' => 'App ID',
            'app_id_info' => 'Your Firebase app ID (1:123456789:web:...)',
            'measurement_id' => 'Measurement ID',
            'measurement_id_info' => 'Your Firebase measurement ID for Analytics (G-XXXXXXXXXX)',
            'vapid_key' => 'VAPID Key',
            'vapid_key_info' => 'Your Firebase VAPID key for push notifications (optional)',
        ],
        'settings' => [
            'title' => 'Notification Settings',
            'info' => 'Configure notification behavior and appearance',
            'enable_notifications' => 'Enable Notifications',
            'enable_notifications_info' => 'Enable or disable real-time notifications',
            'notification_duration' => 'Notification Duration (ms)',
            'notification_duration_info' => 'How long notifications should be displayed (1000-30000 milliseconds)',
            'default_icon' => 'Default Icon',
            'default_icon_info' => 'Default icon for notifications',
            'auto_close' => 'Auto Close',
            'auto_close_info' => 'Automatically close notifications after duration',
        ],
    ],
];