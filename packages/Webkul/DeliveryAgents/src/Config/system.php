<?php

return [
    [
        'key'  => 'delivery',
        'name' => 'deliveryAgent::app.deliveryAgent.system.title', // "نظام التوصيل"
        'info' => 'deliveryAgent::app.deliveryAgent.system.info', // "إعدادات النظام الخاصة بالتوصيل"
        'sort' => 3,
    ],

    [
        'key'  => 'delivery.settings',
        'name' => 'deliveryAgent::app.deliveryAgent.system.settings.title', // "نظام التوصيل"
        'info' => 'deliveryAgent::app.deliveryAgent.system.settings.info', // "إعدادات النظام الخاصة بالتوصيل"
        'icon' => 'settings/truck1.svg',
        'sort' => 1,

    ],
    [
        'key'    => 'delivery.settings.general',
        'name'   => 'deliveryAgent::app.deliveryAgent.system.settings.general.title', // "الإعدادات العامة"
        'info'   => 'deliveryAgent::app.deliveryAgent.system.settings.general.info',    // "إعدادات عامة للنظام"
        'icon'   => 'settings/truck1.svg',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'enable_delivery_system',
                'title'         => 'deliveryAgent::app.deliveryAgent.system.settings.general.fields.enable-delivery-system', // "تفعيل نظام التوصيل"
                'type'          => 'boolean',
                'default_value' => true,
                'channel_based' => false,
                'locale_based'  => false,
            ],
        ],
    ],

    // إعدادات المتجر
    [
        'key'    => 'delivery.settings.store',
        'name'   => 'deliveryAgent::app.deliveryAgent.system.settings.store.title', // "إعدادات المتجر"
        'info'   => 'deliveryAgent::app.deliveryAgent.system.settings.store.store-info', // "إعدادات عامة للمتجر"
        'icon'   => 'settings/store.svg',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'default_country',
                'title'         => 'deliveryAgent::app.deliveryAgent.system.settings.store.fields.default-country', // "الدولة الافتراضية للمتجر"
                'type'          => 'country',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ],

    // إعدادات المندوب
    [
        'key'    => 'delivery.settings.agent',
        'name'   => 'deliveryAgent::app.deliveryAgent.system.settings.agent.title', // "إعدادات المندوب"
        'info'   => 'deliveryAgent::app.deliveryAgent.system.settings.agent.info', // "إعدادات عامة للمندوب"
        'sort'   => 2,
        'fields' => [

        ],
    ],

    // إعدادات نطاقات المندوب
    [
        'key'    => 'delivery.settings.ranges',
        'name'   => 'deliveryAgent::app.deliveryAgent.system.settings.ranges.title', // "إعدادات نطاقات التوصيل"
        'info'   => 'deliveryAgent::app.deliveryAgent.system.settings.ranges.info', // "إعدادات تحديد نطاقات المندوب"
        'sort'   => 3,
        'fields' => [
            [
                'name'          => 'allow_multiple_ranges',
                'title'         => 'deliveryAgent::app.deliveryAgent.system.settings.ranges.fields.allow-multiple-ranges', // "السماح لعدة نطاقات توصيل"
                'type'          => 'boolean',
                'default_value' => true,
                'channel_based' => false,
                'locale_based'  => false,
            ],
        ],
    ],

    // إعدادات طلبات المندوب
    [
        'key'    => 'delivery.settings.orders',
        'name'   => 'deliveryAgent::app.deliveryAgent.system.settings.orders.title', // "إعدادات الطلبات"
        'info'   => 'deliveryAgent::app.deliveryAgent.system.settings.orders.info', // "إعدادات التعامل مع طلبات المندوب"
        'sort'   => 4,
        'fields' => [

        ],
    ],
];
