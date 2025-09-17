<?php

return [
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
            'rejected_by_agent'   => 'المندوب رفض عن الطلب',
            'out_for_delivery'    => 'الطلب في الطريق',
        ],
    ],
];
