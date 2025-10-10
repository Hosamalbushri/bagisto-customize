<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'location' => [
                    'title' => 'إعدادات الموقع',
                    'info'  => 'تكوين إعدادات الموقع والمنطقة الجغرافية للمتجر',
                    'store' => [
                        'title'      => 'إعدادات المتجر الأساسية',
                        'store-info' => 'تكوين الإعدادات الأساسية للمتجر مثل الدولة الافتراضية',
                        'fields'     => [
                            'default-country' => 'الدولة الافتراضية',
                        ],
                    ],
                ],
                'graphql' => [
                    'notification-settings' => [
                        'title'                                  => 'إعدادات الإشعارات',
                        'info'                                   => 'إعدادات الإشعارات الفورية للعملاء',
                        'enable-order-status-notifications'      => 'تفعيل إشعارات تغيير حالة الطلب',
                        'enable-order-status-notifications-info' => 'عند التفعيل، سيتم إرسال إشعارات للعملاء عند تغيير حالة طلباتهم',
                        'enable-order-comment-notifications'      => 'تفعيل إشعارات تعليقات الطلب',
                        'enable-order-comment-notifications-info' => 'عند التفعيل، سيتم إرسال إشعارات للعملاء عند إضافة تعليقات على طلباتهم',
                        'enable-customer-note-notifications'      => 'تفعيل إشعارات ملاحظات العميل',
                        'enable-customer-note-notifications-info' => 'عند التفعيل، سيتم إرسال إشعارات للعملاء عند إضافة ملاحظات على حساباتهم',
                    ],
                ],
            ],
            'catalog' => [
                'products' => [
                    'create' => [
                        'title'                                => 'إعدادات إنشاء المنتجات',
                        'title-info'                           => 'تكوين الإعدادات لإنشاء منتجات جديدة',
                        'enable-default-product-type'          => 'تفعيل اختيار نوع المنتج الافتراضي',
                        'enable-default-product-type-info'     => 'السماح للمستخدمين بتعيين نوع منتج افتراضي للمنتجات الجديدة',
                        'default-product-type'                 => 'نوع المنتج الافتراضي',
                        'default-product-type-info'            => 'اختر نوع المنتج الافتراضي عند إنشاء منتجات جديدة',
                        'auto-generate-sku'                    => 'توليد رمز المنتج تلقائياً',
                        'auto-generate-sku-info'               => 'توليد رمز المنتج تلقائياً للمنتجات الجديدة',
                        'sku-prefix'                           => 'بادئة رمز المنتج',
                        'sku-prefix-info'                      => 'البادئة التي ستضاف إلى أرقام المنتجات المولدة تلقائياً (مثل: PRD، PROD). اتركها فارغة للحصول على أرقام رقمية فقط.',
                        'sku-length'                           => 'طول رمز المنتج',
                        'sku-length-info'                      => 'طول الجزء الرقمي في أرقام المنتجات المولدة تلقائياً (3-10 أرقام)',
                        'enable-default-attribute-family'      => 'تفعيل اختيار العائلة الافتراضية',
                        'enable-default-attribute-family-info' => 'السماح للمستخدمين بتعيين عائلة افتراضية للمنتجات الجديدة',
                        'default-attribute-family'             => 'العائلة الافتراضية',
                        'default-attribute-family-info'        => 'اختر العائلة الافتراضية عند إنشاء منتجات جديدة',
                        'auto-selected-family'                 => 'تم اختيار العائلة تلقائياً',
                        'auto-fill-required-fields'            => 'ملء الحقول المطلوبة تلقائياً',
                        'auto-fill-required-fields-info'       => 'ملء الحقول المطلوبة تلقائياً بقيم افتراضية',
                        'sample-sku'                           => 'معاينة رمز المنتج',
                        'generate-sample'                      => 'توليد عينة',
                        'default-product-type-selected'        => 'تم تحديد نوع المنتج الافتراضي',
                        'auto-generated-sku'                   => 'رمز المنتج مولد تلقائياً',
                    ],
                ],
            ],
            'customer'=> [
                'address'=> [
                    'options'=> [
                        'title'                  => 'خيارات العنوان',
                        'info'                   => 'تكوين الحقول التي تظهر في نماذج العنوان',
                        'show-company-name'      => 'إظهار حقل اسم الشركة',
                        'show-company-name-info' => 'إظهار حقل اسم الشركة في نماذج العنوان',
                        'show-tax-number'        => 'إظهار حقل رقم الضريبة',
                        'show-tax-number-info'   => 'إظهار حقل رقم الضريبة في نماذج العنوان',
                        'show-postal-code'       => 'إظهار حقل الرمز البريدي',
                        'show-postal-code-info'  => 'إظهار حقل الرمز البريدي في نماذج العنوان',
                    ],
                ],
            ],
        ],
    ],
    /*
       |--------------------------------------------------------------------------
       | قسم المناطق (المدن والولايات)
       |--------------------------------------------------------------------------
       */

    'country' => [

        'menu' => [
            'title'     => 'إدارة المواقع',
            'countries' => 'البلدان',
        ],

        'acl' => [
            'title'         => 'إدارة المواقع',
            'countries'     => 'البلدان',
            'create'        => 'إضافة ',
            'edit'          => 'تحرير ',
            'delete'        => 'حذف ',
        ],

        'dataGrid' => [
            'id'            => 'المعرف',
            'name'          => 'اسم البلدة',
            'code'          => 'رمز البلدة',
            'states_count'  => 'عدد المناطق',
            'actions'       => [
                'view'  => 'عرض',
                'delete'=> 'حذف',
            ],
            'delete-success'             => 'تم الحذف  بنجاح',
            'no-found'                   => 'فشل الحذف ',
            'mass-delete-success'        => 'تم حذف البلدان المحددة  بنجاح',
            'delete_warning_has_children'=> 'لا يمكن حذف البلدة لأنها تحتوي على ابناء.',
        ],

        'index' => [
            'title' => 'قائمة البلدان المتاحة',
        ],

        'create' => [
            'title'             => 'إضافة بلدة جديدة',
            'name'              => 'اسم البلدة',
            'code'              => 'رمز البلدة',
            'create-btn'        => 'حفظ ',
            'index-create-btn'  => 'إضافة بلدة',
            'create-success'    => 'تمت إضافة البلدة بنجاح.',
        ],

        'view' => [
            'title'               => 'تفاصيل البلدة',
            'back-btn'            => 'رجوع',
            'delete-btn'          => 'حذف البلدو',
            'country'             => 'البلدة',
            'name'                => 'اسم البلدة',
            'code'                => 'رمز البلدة',

            'states' => [
                'count'         => 'عدد المناطق :count',
                'create-btn'    => 'إضافة منطقة جديدة',
            ],
        ],

        'edit' => [
            'title'         => 'تحرير بيانات البلدة',
            'name'          => 'اسم البلدة',
            'code'          => 'رمز البلدة',
            'edit-btn'      => 'تحرير',
            'edit-success'  => 'تم تحديث بيانات البلدة بنجاح.',
        ],

        'state' => [
            'acl' => [
                'states'        => 'المناطق',
                'view'          => 'عرض ',
                'create'        => 'إضافة ',
                'edit'          => 'تحرير ',
                'delete'        => 'حذف ',
            ],
            'create' => [
                'title'             => 'إضافة منطقة جديدة',
                'name'              => 'اسم المنطقة',
                'code'              => 'رمز المنطقة',
                'create-btn'        => 'حفظ المنطقة',
                'create-success'    => 'تم حفظ المنطقة بنجاح.',
            ],
            'view' => [
                'title'                => 'تفاصيل المنطقة ',
                'back-btn'             => 'رجوع',
                'delete-btn'           => 'حذف المنطقة',
                'state'                => 'المنطقة',
                'create-area-btn'      => 'إضافة مدينة - حي سكني',
                'name'                 => 'اسم المنطقة',
                'code'                 => 'رمز المنطقة',
            ],
            'edit' => [
                'title'         => 'تحرير بيانات المنطقة',
                'name'          => 'اسم المنطقة',
                'code'          => 'رمز المنطقة',
                'edit-btn'      => 'تحرير',
                'edit-success'  => 'تم تحديث بيانات المنطقة بنجاح.',
            ],

            'dataGrid' => [
                'id'                => 'الـمعرف',
                'name'              => 'اسم المنطقة',
                'code'              => 'رمز المنطقة',
                'actions'           => [
                    'view'  => 'عرض',
                    'delete'=> 'حذف',
                ],
                'delete-success'             => 'تم الحذف المنطقة بنجاح',
                'no-found'                   => 'لم يتم المنطقة الحذف بنجاح',
                'mass-delete-success'        => 'تم حذف المناطق المحددة  بنجاح',
                'delete_warning_has_children'=> 'لا يمكن حذف المنطقة لأنها تحتوي على ابناء.',

            ],
            'area'=> [
                'create'=> [
                    'title'         => 'إضافة مدينة / حي جديد',
                    'name'          => 'اسم المدينة او الحــي',
                    'save-btn'      => 'حفظ',
                    'create-success'=> 'تم اضافة المدينة بنجاح',

                ],
                'edit'=> [
                    'title'         => 'تعديل المدينة او الحــي ',
                    'name'          => 'اسم المدينة او الحــي',
                    'edit-btn'      => 'تعديل',
                    'edit-success'  => 'تم التحديث بنجاح',
                ],
                'view'=> [
                    'title'   => 'قائمة مناديب ',
                    'add-btn' => 'اضف مندوب',
                    'drawer'  => [
                        'header'=> 'اضافة مناديب فــي ',
                    ],
                    'dataGrid'=> [
                        'delete-from-area'    => 'ازالة من المنطقة',
                        'add-to-area'         => 'إضافة إلى المنطقة',
                        'add-success'         => 'تمت إضافة المناديب المحددين بنجاح إلى هذه المنطقة',
                        'deleted-success'     => 'تمت ازالة المناديب المحددين من هذه المنطقة',

                    ],

                ],
                'dataGrid'=> [
                    'id'                   => 'المعرف',
                    'name'                 => 'اسم المدينة او الحــي',
                    'delivery-count'       => 'عدد المناديب',
                    'actions'              => [
                        'edit'  => 'تعديل',
                        'delete'=> 'حذف',
                    ],
                    'delete-success'             => 'تم الحذف بنجاح',
                    'no-found'                   => 'فشلت عملية الحذف',
                    'delete_warning_has_children'=> 'لا يمكن حذف المدينة لأنها تحتوي على ابناء.',

                ],
                'acl'=> [
                    'areas'         => 'المدن او الاحياء',
                    'view'          => 'عرض ',
                    'create'        => 'إضافة ',
                    'edit'          => 'تحرير ',
                    'delete'        => 'حذف ',
                ],
            ],
        ],
    ],
    'reviews' => [
        'index' => [
            'title'=> 'المنتجات',
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
                'view'=> [
                    'address'=> [
                        'area-not-found' => 'المنطقة المحددة غير موجودة.',
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

        'push-notifications' => [
            'order-status-update' => [
                'title'              => 'تحديث حالة الطلب',
                'body'               => 'تم تحديث حالة طلبك رقم :order_number إلى: :status',
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
            'order-comment' => [
                'title' => 'تم إضافة تعليق على الطلب',
                'body'  => 'تم إضافة تعليق جديد على طلبك رقم :order_number: :comment',
            ],
            'customer-note' => [
                'title' => 'تم إضافة ملاحظة على الحساب',
                'body'  => 'تم إضافة ملاحظة جديدة على حسابك: :note',
            ],
        ],
    ],
];
