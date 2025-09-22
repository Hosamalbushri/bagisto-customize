<?php

return [

    /*
    |--------------------------------------------------------------------------
    | قسم موظفي التوصيل
    |--------------------------------------------------------------------------
    */

    'deliveryAgent' => [

        'menu' => [
            'title'             => 'خدمات التوصيل',
            'delivery-agents'   => 'مندوبين التوصيل',
        ],

        'acl' => [
            'title'             => 'خدمات التوصيل',
            'delivery-agents'   => 'مندوبين التوصيل',
            'create'            => 'إضافة',
            'edit'              => 'تحرير',
            'delete'            => 'حذف',
        ],
        'system' => [
            'title'    => 'إدارة التوصيل',
            'info'     => 'إعدادات شاملة خاصة بخدمة التوصيل',

            'settings' => [
                'title'    => 'إعدادات التوصيل',
                'info'     => 'إدارة وضبط إعدادات خدمة التوصيل',

                'general'  => [
                    'title'  => 'الإعدادات العامة',
                    'info'   => 'ضبط الإعدادات الأساسية الخاصة بنظام التوصيل',
                    'fields' => [
                        'enable-delivery-system'=> 'تفعيل خدمة التوصيل',
                    ],
                ],

                'store' => [
                    'title'      => 'إعدادات المتجر',
                    'store-info' => 'ضبط الإعدادات المتعلقة بالمتجر',
                    'fields'     => [
                        'default-country' => 'الدولة الافتراضية للمتجر',
                    ],
                ],

                'agent' => [
                    'title'  => 'إعدادات المندوب',
                    'info'   => 'التحكم بالإعدادات العامة الخاصة بالمناديب',
                    'fields' => [
                        // هنا ممكن نضيف لاحقًا إعدادات مثل "عدد الطلبات القصوى للمندوب"
                    ],
                ],

                'ranges' => [
                    'title'  => 'إعدادات نطاقات التوصيل',
                    'info'   => 'التحكم بطريقة تحديد النطاقات للمندوبين',
                    'fields' => [
                        'allow-multiple-ranges' => 'السماح بإنشاء عدة نطاقات للمندوب',
                    ],
                ],

                'orders' => [
                    'title'  => 'إعدادات الطلبات',
                    'info'   => 'إدارة الإعدادات الخاصة بطلبات التوصيل',
                    'fields' => [
                        'allow_agent_acceptance'=> 'السماح لمندوب التوصيل بقبول او رفض الطلبات',
                        'show_agent_data_to_customer'=> 'عرض بيانات المندوب للعميل (الاسم ورقم الهاتف)',
                        'allow_agent_rating'=> 'السماح بتقييم المناديب',
                    ],
                ],
            ],
        ],

        'index' => [
            'title' => 'قائمة مناديب التوصيل',
        ],

        'view' => [
            'title'         => 'المندوب',
            'back-btn'      => 'رجوع إلى القائمة',
            'delivery-agent'=> 'اسم المندوب',
            'first-name'    => 'الاسم الأول',
            'last-name'     => 'الاسم الأخير',
            'phone'         => 'رقم التواصل - :phone',
            'email'         => 'البريد الإلكتروني - :email',
            'date-of-birth' => 'تاريخ الميلاد - :dob',
            'gender'        => 'النوع - :gender',
            'status'        => 'الحالة الحالية',
            'active'        => 'مفعل',
            'inactive'      => 'غير مفعل',
            'edit-btn'      => 'تحرير',
            'dataGrid'      => [
                'orders'        => [
                    'count'      => 'الطلبيات',
                    'empty-order'=> 'لا توجد طلبيات',
                ],
            ],

        ],

        'orders'=> [
            'view'=> [
                'accepted-order-confirmation'        => 'هل أنت متأكد أنك تريد قبول هذا الطلب؟',
                'rejected-order-confirmation'        => 'هل أنت متأكد أنك تريد رفض هذا الطلب؟',
                'out-for-delivery-order-confirmation'=> 'هل أنت متأكد من تغيير حالة الطلب إلى "جاري التوصيل"؟ بعد التأكيد، لايمكنك  تعديل حالة الطلب.',
                'delivered-order-confirmation'       => 'هل أنت متأكد من تغيير حالة الطلب إلى "تم التوصيل"؟ بعد التأكيد، سيتم إغلاق الطلب ولن يمكن تعديله.',
            ],
            'status'=> [
                'assigned_to_agent'   => 'طلب جديد',
                'accepted_by_agent'   => ' طلب مقبول',
                'rejected_by_agent'   => 'طلب مرفوض ',
                'out_for_delivery'    => 'جاري التوصيل',
                'delivered'           => 'تم التوصيل',
            ],
            'actions'=> [
                'accept_btn'          => 'قبول',
                'reject_btn'          => 'رفض',
                'out_for_delivery_btn'=> 'توصيل',
                'delivered_btn'       => 'تسليم',
            ],
            'acl'=> [
                'accept'          => 'قبول الطلب',
                'reject'          => ' رفض الطلب',
                'out_for_delivery'=> 'توصيل الطلب',
                'delivered'       => 'تسليم الطلب',
            ],
        ],

        'create' => [
            'title'             => 'إضافة مندوب توصيل جديد',
            'create-btn'        => 'حفظ البيانات',
            'create'            => 'إضافة مندوب',
            'first-name'        => 'الاسم الأول',
            'last-name'         => 'الاسم الأخير',
            'phone'             => 'رقم الهاتف',
            'email'             => 'البريد الإلكتروني',
            'date-of-birth'     => 'تاريخ الميلاد',
            'gender'            => 'النوع',
            'select-gender'     => 'اختر النوع',
            'male'              => 'ذكر',
            'female'            => 'أنثى',
            'other'             => 'أخرى',
            'password'          => 'كلمة المرور الجديدة',
            'confirm-password'  => 'تأكيد كلمة المرور',
            'status'            => 'حالة الحساب',
            'select-status'     => 'اختر الحالة',
            'active'            => 'مفعل',
            'inactive'          => 'متوقف مؤقتًا',
            'create-success'    => 'تمت إضافة الموظف بنجاح.',
        ],

        'delete' => [
            'successful_deletion_message'   => 'تم حذف الموظف بنجاح.',
            'unsuccessful_deletion_message' => 'تعذر حذف الموظف. حاول لاحقًا.',
        ],

        'edit' => [
            'title'                       => 'تحرير بيانات المندوب',
            'edit-btn'                    => 'حفظ التعديلات',
            'save-btn'                    => 'تحديث',
            'edit-success'                => 'تم تحديث بيانات المندوب بنجاح.',
            'first-name'                  => 'الاسم الأول',
            'last-name'                   => 'الاسم الأخير',
            'phone'                       => 'رقم الهاتف',
            'email'                       => 'البريد الإلكتروني',
            'date-of-birth'               => 'تاريخ الميلاد',
            'gender'                      => 'النوع',
            'select-gender'               => 'اختر النوع',
            'male'                        => 'ذكر',
            'female'                      => 'أنثى',
            'other'                       => 'أخرى',
            'current_password'            => 'كلمة المرور السابقة ',
            'password'                    => 'كلمة المرور الجديدة',
            'confirm-password'            => 'تأكيد كلمة المرور',
            'status'                      => 'حالة الحساب',
            'select-status'               => 'اختر الحالة',
            'active'                      => 'مفعل',
            'inactive'                    => 'متوقف مؤقتًا',
            'incorrect_current_password'  => 'كلمة المرور السابقة غير متطابقة ',

        ],

        'dataGrid' => [
            'id'                => 'المعرف',
            'id-value'          => 'المعرف - :id',
            'name'              => 'اسم المندوب',
            'phone'             => 'رقم الهاتف',
            'email'             => 'البريد الإلكتروني',
            'gender'            => 'الجنس',
            'status'            => 'الحالة',
            'active'            => 'مفعل',
            'inactive'          => 'متوقف',
            'range-count'       => 'عدد النطاقات',
            'order_count'       => 'الطلبيات الحالية ',
            'has_orders'        => 'لديه طلبيات',
            'no_orders'         => 'لا يوجد طلبيات',
            'no-order'          => 'لا يوجد طلبيات حالية',
            'range'             => ':range نطاق(نطاقات)',
            'order'             => ':order طلب(طلبيات)',
            'country'           => 'الدولة',
            'rating'            => 'التقييم',
            'state'             => 'الولاية',
            'actions'           => [
                'view'  => 'عرض',
                'delete'=> 'حذف',
            ],
            'delete'                        => 'حذف',
            'update-status'                 => 'تحديث الحالة',
            'delete-success'                => 'تم حذف المندوب بنجاح',
            'update-success'                => 'تم نحديث الحالة بنجاح',
            'unsuccessful_deletion_message' => 'لا يمكن حذف المندوب لوجود طلبات غير مكتملة.',

            'orders'        => [
                'status'=> [
                    'assigned_to_agent'   => 'الطلبات الجديدة',
                    'accepted_by_agent'   => 'الطلبات المقبولة',
                    'rejected_by_agent'   => 'الطلبات المرفوضة',
                    'out_for_delivery'    => 'الطلبات قيد التوصيل',
                    'delivered'           => 'الطلبات المكتملة',
                ],
            ],
        ],
        'reviews' => [
            'index' => [
                'title'             => 'تقييمات مندوبي التوصيل',
                'count'             => 'عدد التقييمات (:count)',
                'datagrid'          => [
                    'review-id'         => 'المعرف: :review_id',
                    'empty-reviews'     => 'لا توجد تقييمات متاحة',
                    'id'                => 'المعرف',
                    'order_id'          => 'رقم الطلب',
                    'delivery_agent'    => 'مندوب التوصيل',
                    'customer'          => 'العميل',
                    'rating'            => 'التقييم',
                    'comment'           => 'التعليق',
                    'created_at'        => 'تاريخ الإنشاء',
                    'status'            => [
                        'status'        => 'الحالة',
                        'pending'       => 'معلق',
                        'approved'      => 'موافق علية',
                        'disapproved'   => 'غير موافق علية',
                    ],
                ],
            ],

        ],

    ],
    /*
   |--------------------------------------------------------------------------
   | قسم النطاقات الخاصة بالمندوبين والمناطق
   |--------------------------------------------------------------------------
   */
    'range'=> [
        'index' => [
            'title' => 'قائمة المدن المتاحة',

        ],
        'acl'=> [
            'title'         => 'نطاقات المندوب',
            'create'        => 'إضافة ',
            'edit'          => 'تحرير ',
            'delete'        => 'حذف ',
        ],
        'create' => [
            'title'                      => 'إضافة نطاق جديد',
            'area-name'                  => ' المدينة او الحــي',
            'country'                    => ' البلدة',
            'state'                      => 'المنطقة',
            'create-btn'                 => 'حفظ النطاق',
            'index-create-btn'           => 'إضافة',
            'select_country'             => 'اختر البلدة',
            'select_state'               => 'اختر المنطقة',
            'select_state_area'          => 'اختر الدولة او الحــي',
            'add_state'                  => 'اضافة منطقة',
            'add_area'                   => 'اضافة دولة او حــي',
            'no_states_for_country'      => 'لا توجد مناطق لهذه البلدة. الرجاء اختيار بلدة تحتوي على مناطق او اضف منطقة جديدة.',
            'no_areas_for_state'         => 'لا توجد دولة او حــي لهذه المنطقة. الرجاء اختيار منطقة تحتوي على دول او احياء او اضف دولة او حــي .',
            'create-success'             => 'تمت إضافة النطاق للمندوب بنجاح.',
            'create-failed'              => 'هذا المندوب مسجل بالفعل في هذه المنطقة الجغرافية.',
            'multiple-not-allowed'       => 'لا يمكن إضافة أكثر من نطاق لهذا المندوب.',
        ],
        'view'=> [
            'count'                     => 'عدد النطاقات (:count)',
            'empty-title'               => 'إضافة نطاق للمندوب',
            'empty-description'         => 'إنشاء نطاقات جديدة للمندوب',
            'delete-btn'                => 'حذف',
            'range-delete-confirmation' => 'هل أنت متأكد أنك تريد حذف هذا النطاق؟',
            'range-delete-success'      => 'تم حذف النطاق بنجاح',
            'range-delete-failed'       => 'لم يتم الحذف حاول مرة اخرى لاحقا',
        ],
        'edit'=> [
            'title'                      => 'تعديل النطاق الحالي',
            'area-name'                  => ' الدولة او الحــي',
            'country'                    => ' البلدة',
            'state'                      => 'المنطقة',
            'view-edit-btn'              => 'تعديل',
            'edit-btn'                   => 'تحديث',
            'index-create-btn'           => 'إضافة',
            'select_country'             => 'اختر البلدة',
            'select_state'               => 'اختر المنطقة',
            'select_state_area'          => 'اختر الدولة او الحــي',
            'add_state'                  => 'اضافة منطقة',
            'add_area'                   => 'اضافة دولة او حــي',
            'no_states_for_country'      => 'لا توجد مناطق لهذه البلدة. الرجاء اختيار بلدة تحتوي على مناطق او اضف منطقة جديدة.',
            'no_areas_for_state'         => 'لا توجد دولة او حــي لهذه المنطقة. الرجاء اختيار منطقة تحتوي على دول او احياء او اضف دولة او حــي .',
            'edit-success'               => 'تم تحديث بيانات النطاق بنجاح.',
            'edit-failed'                => 'هذا المندوب مسجل بالفعل في هذه المنطقة الجغرافية',

        ],

    ],
    /*
|--------------------------------------------------------------------------
| قسم  التقييمات والمراجعات
|--------------------------------------------------------------------------
*/
    'review' => [
        'index' => [
            'title'             => 'تقييمات مندوبي التوصيل',
            'id'                => 'المعرف',
            'order_id'          => 'رقم الطلب',
            'delivery_agent'    => 'مندوب التوصيل',
            'customer'          => 'العميل',
            'rating'            => 'التقييم',
            'comment'           => 'التعليق',
            'created_at'        => 'تاريخ الإنشاء',
            'tab'               => [
                'title'=> 'المناديب',
            ],
            'status'            => [
                'status'        => 'الحالة',
                'pending'       => 'معلق',
                'approved'      => 'موافق علية',
                'disapproved'   => 'غير موافق علية',
            ],
            'count'             => 'عدد التقييمات (:count)',
            'datagrid'          => [
                'review-id'         => 'المعرف: :review_id',
                'empty-reviews'     => 'لا توجد تقييمات متاحة',
                'actions'           => [
                    'update-status' => 'تحديث الحالة',
                    'delete'        => 'حذف',
                    'edit'          => 'تحديث',
                ],
                'status'            => [
                    'status'        => 'الحالة',
                    'pending'       => 'معلق',
                    'approved'      => 'موافق علية',
                    'disapproved'   => 'غير موافق علية',
                ],
                'delete'=> [
                    'mass-delete-success'=> 'تم حذف التقييمات المحددة بنجاح',
                    'mass-delete-error'  => 'حدث خطأ أثناء الحذف الجماعي التقييمات',
                ],
                'update'=> [
                    'mass-update-success'=> 'تم تحديث التقييمات المحددة بنجاح',
                    'mass-update-error'  => 'حدث خطأ أثناء التحديث الجماعي التقييمات',

                ],
            ],
            'edit' => [
                'title'         => 'تحرير التقييم',
                'save-btn'      => 'حفظ',
                'agent'         => 'مندوب التوصيل',
                'customer'      => 'العميل',
                'order_id'      => 'رقم الطلب',
                'date'          => 'التاريخ',
                'status'        => 'الحالة',
                'rating'        => 'التقييم',
                'comment'       => 'التعليق',
                'pending'       => 'معلق',
                'approved'      => 'موافق علية',
                'disapproved'   => 'غير موافق علية',
                'update_success'=> 'تم تحديث التقييم بنجاح',
                'update_error'  => 'حدث خطأ أثناء تحديث التقييم',

            ],
            'delete' => [
                'delete_success'=> 'تم حذف التقييم بنجاح',
                'delete_error'  => 'حدث خطأ أثناء حذف التقييم',
            ],
        ],

    ],

    /*
|--------------------------------------------------------------------------
| قسم  الطلبات
|--------------------------------------------------------------------------
*/
    'select-order'=> [
        'index'=> [
            'select-delivery-agent-btn'     => 'تعيين مندوب التوصيل',
            'reselect-delivery-agent-btn'   => 'اعادة تعيين مندوب للطلب',
            'select-delivery-agent'         => 'تعيين المندوب للطلب رقــم #',
            'assign-btn'                    => 'تعيين',
            'assigning'                     => 'جاري تعيين المندوب',
            'processing'                    => 'قيد المعالجة',
            'please-wait'                   => 'يرجى الانتظار بينما نقوم بمعالجة طلبك',
            'in-progress'                   => 'قيد التنفيذ',
            'tabs'                          => [
                'in-the-same-area'=> 'المتواجدون في :city',
                'all'             => 'كل المناديب',
            ],
            'assign-delivery-agent-confirmation'=> ' هل انت متاكد انك تريد تعيين هذا المندوب لهذا الطلب ',
        ],
        'create'=> [
            'create-success'    => 'تم تعيين المندوب بنجاح',
            'create-error'      => ' عذرا المندوب غير مفعل يرجى تفعيل المندوب اولا.',
            'order-has-delivery'=> 'تم تعيين مندوب مسبقًا لهذا الطلب',
            'transaction-failed'=> 'فشل في تعيين المندوب. يرجى المحاولة مرة أخرى.',
        ],
        'update'=> [
            'update-failed' => 'عذا لايمكنك التعديل في حالة الطلب',
            'update-success'=> 'تم تحديث حالة الطلب بنجاح',
            'updated-error' => 'حدث خطا عند التعديل تحقق من  حالة المندوب ',
        ],
    ],
    'orders'=> [
        'view'=> [
            'delivery'                       => 'مندوب التوصيل',
            'no-delivery-agent-found'        => 'لم يتم تعيين مندوب لهذا الطلب',
            'view'                           => 'عرض',
            'contact'                        => 'اتصال',
            'item-delivered'                 => 'تم التوصيل (:qty_delivered)',

        ],
        'acl' => [
            'title'             => 'الطلـبيات',
            'select-delivery'   => 'تعيين مندوب للطلب',
            'create'            => 'إضافة',
            'edit'              => 'تحرير',
            'delete'            => 'حذف',
        ],
        'status' => [
            'pending'             => 'قيد الانتظار',
            'pending_payment'     => 'بانتظار الدفع',
            'processing'          => 'قيد المعالجة',
            'completed'           => 'مكتمل',
            'canceled'            => 'ملغي',
            'closed'              => 'مغلق',
            'fraud'               => 'احتيال',
            'assigned_to_agent'   => 'تم تعيين المندوب',
            'accepted_by_agent'   => ' تم قبول الطلب',
            'rejected_by_agent'   => 'تم رفض الطلب ',
            'out_for_delivery'    => 'قيد التوصيل',
            'delivered'           => 'تم التوصيل',
        ],
    ],
    'notifications'=> [
        'order-status-messages'=> [

            'assigned_to_agent'=> 'تم تعيين المندوب',
        ],
    ],

    // for shop pages
    'shop'=> [
        'customer'=> [
            'account'=> [
                'orders'=> [
                    'view'=> [
                        'delivered'=> [
                            'delivery'=> 'التوصيل',
                        ],
                    ],
                ],
            ],
        ],
        'deliveryAgent'=> [
            'review'=> [
                'create'=> [
                    'title'     => 'انشاء تقييم لمندوب التوصيل',
                    'create'    => 'تقييم المندوب',
                    'create-btn'=> 'ارسال التقييم',
                ],
                'validation'=> [
                    'already_reviewed'   => 'تم تقييم هذا الطلب مسبقاً',
                    'order_not_delivered'=> 'لا يمكن تقييم طلب غير مكتمل التوصيل',
                    'duplicate_review'   => 'لا يمكن إضافة أكثر من مراجعة واحدة لكل طلب',
                    'review_success'     => 'تم إرسال التقييم بنجاح',
                    'review_error'       => 'حدث خطأ أثناء إرسال التقييم',
                    'checking_review'    => 'جاري التحقق من وجود مراجعة مسبقة...',
                    'review_exists'      => 'تم تقييم هذا الطلب مسبقاً',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | رسائل GraphQL API
    |--------------------------------------------------------------------------
    */
    'app' => [
        'orders' => [
            'success' => [
                'accepted' => 'تم قبول الطلب بنجاح',
                'rejected' => 'تم رفض الطلب بنجاح',
                'status_updated' => 'تم تحديث حالة الطلب بنجاح',
                'completed' => 'تم إكمال الطلب بنجاح',
            ],
            'errors' => [
                'unauthorized' => 'وصول غير مصرح به',
                'order_not_found' => 'الطلب غير موجود',
                'invalid_status_transition' => 'انتقال حالة غير صالح',
            ],
        ],
        'reviews' => [
            'success' => [
                'created' => 'تم إنشاء التقييم بنجاح',
                'updated' => 'تم تحديث التقييم بنجاح',
                'deleted' => 'تم حذف التقييم بنجاح',
            ],
            'errors' => [
                'unauthorized' => 'وصول غير مصرح به',
                'not_found' => 'التقييم غير موجود',
                'already_exists' => 'يوجد تقييم مسبق لهذا الطلب',
                'invalid_rating' => 'يجب أن يكون التقييم بين 1 و 5',
                'cannot_update' => 'لا يمكن تحديث التقييم المعتمد',
                'cannot_delete' => 'لا يمكن حذف التقييم المعتمد',
            ],
            'status' => [
                'pending' => 'معلق',
                'approved' => 'معتمد',
                'disapproved' => 'غير معتمد',
            ],
        ],
    ],

];
