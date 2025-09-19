<?php

return [
    'configuration' => [
        'index' => [
            'catalog' => [
                'products' => [
                    'create' => [
                        'title'                            => 'إعدادات إنشاء المنتجات',
                        'title-info'                       => 'تكوين الإعدادات لإنشاء منتجات جديدة',
                        'enable-default-product-type'      => 'تفعيل اختيار نوع المنتج الافتراضي',
                        'enable-default-product-type-info' => 'السماح للمستخدمين بتعيين نوع منتج افتراضي للمنتجات الجديدة',
                        'default-product-type'             => 'نوع المنتج الافتراضي',
                        'default-product-type-info'        => 'اختر نوع المنتج الافتراضي عند إنشاء منتجات جديدة',
                        'auto-generate-sku'                => 'توليد رمز المنتج تلقائياً',
                        'auto-generate-sku-info'           => 'توليد رمز المنتج تلقائياً للمنتجات الجديدة',
                        'sku-prefix'                       => 'بادئة رمز المنتج',
                        'sku-prefix-info'                  => 'البادئة التي ستضاف إلى أرقام المنتجات المولدة تلقائياً (مثل: PRD، PROD). اتركها فارغة للحصول على أرقام رقمية فقط.',
                        'sku-length'                       => 'طول رمز المنتج',
                        'sku-length-info'                  => 'طول الجزء الرقمي في أرقام المنتجات المولدة تلقائياً (3-10 أرقام)',
                        'auto-fill-required-fields'        => 'ملء الحقول المطلوبة تلقائياً',
                        'auto-fill-required-fields-info'   => 'ملء الحقول المطلوبة تلقائياً بقيم افتراضية',
                        'sample-sku'                       => 'معاينة رمز المنتج',
                        'generate-sample'                  => 'توليد عينة',
                        'default-product-type-selected'    => 'تم تحديد نوع المنتج الافتراضي',
                        'auto-generated-sku'               => 'رمز المنتج مولد تلقائياً',
                    ],
                ],
            ],
        ],
        'reviews' => [
            'index' => [
                'title'=> 'المنتجات',
            ],
        ],
    ],
    'sales' => [
        'orders' => [
            'index' => [
                'datagrid' => [
                    'assigned_to_agent'              => 'تم تعيين المندوب',
                    'accepted_by_agent'              => 'تم قبول الطلب',
                    'canceled'                       => 'تم الإلغاء',
                    'closed'                         => 'مغلق',
                    'completed'                      => 'تم الانتهاء',
                    'fraud'                          => 'احتيال',
                    'out_for_delivery'               => 'جاري التوصيل',
                    'rejected_by_agent'              => 'تم رفض الطلب',
                    'pending-payment'                => 'قيد الدفع',
                    'pending'                        => 'قيد الانتظار',
                    'processing'                     => 'جاري المعالجة',
                ],
            ],
        ],
        'invoices' => [
            'view' => [
                'Pending'              => 'معلقة',
                'Pending Payment'      => 'بانتظار الدفع',
                'Paid'                 => 'مدفوعة',
                'Overdue'              => 'متأخرة',
                'Refunded'             => 'تم استردادها',
            ],
        ],
    ],

    'customers' => [
        'customers' => [
            'view'=> [
                'datagrid'=> [
                    'orders' => [
                        'canceled'           => 'تم الإلغاء',
                        'channel-name'       => 'اسم القناة',
                        'closed'             => 'مغلق',
                        'completed'          => 'مكتمل',
                        'fraud'              => 'احتيال',
                        'pending'            => 'قيد الانتظار',
                        'pending-payment'    => 'قيد الدفع',
                        'processing'         => 'قيد المعالجة',
                        'assigned_to_agent'  => 'بانتظار تأكيد المندوب',
                        'accepted_by_agent'  => 'المندوب استلم الطلب',
                        'rejected_by_agent'  => 'المندوب اعتذر عن الطلب',
                        'out_for_delivery'   => 'الطلب في الطريق',
                    ],
                ],
            ],
        ],
        'reviews' => [
            'index' => [
                'title'=> 'المنتجات',
            ],
        ],
    ],

    'notifications' => [
        'order-status-messages' => [
            'all'                 => 'الكل',
            'canceled'            => 'تم إلغاء الطلب',
            'closed'              => 'تم إغلاق الطلب',
            'completed'           => 'تم الانتهاء من الطلب',
            'pending'             => 'الطلب معلق',
            'pending-payment'     => 'انتظار الدفع',
            'processing'          => 'جاري معالجة الطلب',
            'assigned_to_agent'   => 'بانتظار تأكيد المندوب',
            'accepted_by_agent'   => 'المندوب استلم الطلب',
            'rejected_by_agent'   => 'المندوب رفض  الطلب',
            'out_for_delivery'    => 'الطلب في الطريق',
        ],
    ],
];
