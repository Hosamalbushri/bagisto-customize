<?php

return [
    'configuration' => [
        'firebase' => [
            'title' => 'Firebase Configuration',
            'info' => 'Configure Firebase settings for real-time notifications and analytics',
            'web_project_config' => 'Web Project Configuration',
            'web_project_config_info' => 'Complete Firebase web project configuration JSON object',
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