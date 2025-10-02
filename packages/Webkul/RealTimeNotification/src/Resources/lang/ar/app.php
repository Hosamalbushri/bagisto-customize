<?php

return [
    'configuration' => [
        'firebase' => [
            'title' => 'إعدادات Firebase',
            'info' => 'إعداد إعدادات Firebase للإشعارات الفورية والتحليلات',
            'api_key' => 'مفتاح API',
            'api_key_info' => 'مفتاح Firebase API الخاص بك (AIzaSy...)',
            'auth_domain' => 'نطاق المصادقة',
            'auth_domain_info' => 'نطاق مصادقة Firebase الخاص بك (مثل: project.firebaseapp.com)',
            'project_id' => 'معرف المشروع',
            'project_id_info' => 'معرف مشروع Firebase الخاص بك',
            'storage_bucket' => 'دلو التخزين',
            'storage_bucket_info' => 'دلو تخزين Firebase الخاص بك (مثل: project.firebasestorage.app)',
            'messaging_sender_id' => 'معرف مرسل الرسائل',
            'messaging_sender_id_info' => 'معرف مرسل رسائل Firebase الخاص بك (رقمي)',
            'app_id' => 'معرف التطبيق',
            'app_id_info' => 'معرف تطبيق Firebase الخاص بك (1:123456789:web:...)',
            'measurement_id' => 'معرف القياس',
            'measurement_id_info' => 'معرف قياس Firebase الخاص بك للتحليلات (G-XXXXXXXXXX)',
            'vapid_key' => 'مفتاح VAPID',
            'vapid_key_info' => 'مفتاح VAPID الخاص بك للإشعارات الفورية (اختياري)',
        ],
        'settings' => [
            'title' => 'إعدادات الإشعارات',
            'info' => 'إعداد سلوك ومظهر الإشعارات',
            'enable_notifications' => 'تفعيل الإشعارات',
            'enable_notifications_info' => 'تفعيل أو إلغاء تفعيل الإشعارات الفورية',
            'notification_duration' => 'مدة الإشعار (بالميلي ثانية)',
            'notification_duration_info' => 'كم من الوقت يجب عرض الإشعارات (1000-30000 ميلي ثانية)',
            'default_icon' => 'الأيقونة الافتراضية',
            'default_icon_info' => 'الأيقونة الافتراضية للإشعارات',
            'auto_close' => 'الإغلاق التلقائي',
            'auto_close_info' => 'إغلاق الإشعارات تلقائياً بعد المدة المحددة',
        ],
    ],
];