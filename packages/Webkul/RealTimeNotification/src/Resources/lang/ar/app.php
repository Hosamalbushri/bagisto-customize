<?php

return [
    'configuration' => [
        'firebase' => [
            'title'                   => 'إعدادات Firebase',
            'info'                    => 'إعداد إعدادات Firebase للإشعارات الفورية والتحليلات',
            'web_project_config'      => 'إعدادات مشروع الويب',
            'web_project_config_info' => 'كائن JSON كامل لإعدادات مشروع Firebase للويب',
            'vapid_key'               => 'مفتاح VAPID',
            'vapid_key_info'          => 'مفتاح VAPID الخاص بك للإشعارات الفورية ',
        ],
        'settings' => [
            'title'                      => 'إعدادات الإشعارات',
            'info'                       => 'إعداد سلوك ومظهر الإشعارات',
            'enable_notifications'       => 'تفعيل الإشعارات',
            'enable_notifications_info'  => 'تفعيل أو إلغاء تفعيل الإشعارات الفورية',
            'notification_duration'      => 'مدة الإشعار (بالميلي ثانية)',
            'notification_duration_info' => 'كم من الوقت يجب عرض الإشعارات (1000-30000 ميلي ثانية)',
            'default_icon'               => 'الأيقونة الافتراضية',
            'default_icon_info'          => 'الأيقونة الافتراضية للإشعارات',
            'auto_close'                 => 'الإغلاق التلقائي',
            'auto_close_info'            => 'إغلاق الإشعارات تلقائياً بعد المدة المحددة',
        ],
    ],
];
