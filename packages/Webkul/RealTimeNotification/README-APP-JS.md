# Firebase Real-Time Notification App.js

## نظرة عامة
تم إعادة تنظيم كود Firebase والإشعارات ليكون في ملف `app.js` منفصل بدلاً من تضمينه في ملف Blade.

## الملفات المحدثة

### 1. `src/Resources/assets/js/app.js`
- يحتوي على كلاس `FirebaseNotificationApp` الذي يدير:
  - تهيئة Firebase
  - إعداد Service Worker
  - إدارة FCM Tokens
  - عرض الإشعارات
  - طلب أذونات الإشعارات

### 2. `src/Resources/views/admin/layouts/firebase-cdn.blade.php`
- تم تبسيطه لتمرير الإعدادات فقط
- تحميل `app.js` و `app.css` مباشرة

## الميزات الجديدة

### كلاس FirebaseNotificationApp
```javascript
// تهيئة التطبيق
const app = new FirebaseNotificationApp();

// عرض إشعار
app.showNotification('عنوان الإشعار', 'محتوى الإشعار');

// طلب إذن الإشعارات
app.requestNotificationPermission();

// إرسال إشعار مخصص
app.sendCustomNotification('عنوان', 'محتوى', {data: 'إضافي'});

// الحصول على حالة التطبيق
const status = app.getStatus();
```

### الوظائف المتاحة عالمياً
```javascript
// بعد تحميل الصفحة
window.firebaseNotification.show('عنوان', 'محتوى');
window.firebaseNotification.requestPermission();
window.firebaseNotification.sendCustom('عنوان', 'محتوى', {});
window.firebaseNotification.getStatus();
```

## الاستخدام

### 1. التهيئة التلقائية
يتم تهيئة التطبيق تلقائياً عند تحميل الصفحة.

### 2. عرض الإشعارات
```javascript
// عرض إشعار بسيط
firebaseNotification.show('إشعار جديد', 'لديك طلب جديد');

// إرسال إشعار مخصص
firebaseNotification.sendCustom('طلب جديد', 'طلب #12345', {
    orderId: 12345,
    customerName: 'أحمد محمد'
});
```

### 3. إدارة الأذونات
```javascript
// طلب إذن الإشعارات
firebaseNotification.requestPermission();
```

### 4. مراقبة الحالة
```javascript
// التحقق من حالة التطبيق
const status = firebaseNotification.getStatus();
console.log('تم التهيئة:', status.isInitialized);
console.log('Firebase متاح:', status.hasFirebase);
console.log('الرسائل متاحة:', status.hasMessaging);
```

## التخصيص

### إضافة معالج إشعارات مخصص
```javascript
// في app.js، يمكن إضافة معالجات مخصصة
setupMessageHandlers() {
    this.messaging.onMessage((payload) => {
        // معالجة مخصصة للرسائل
        if (payload.data.type === 'order') {
            this.showOrderNotification(payload);
        }
    });
}
```

### تخصيص تصميم الإشعارات
يمكن تعديل ملف `app.css` لتخصيص تصميم الإشعارات.

## المزايا

1. **تنظيم أفضل**: فصل منطق JavaScript عن Blade templates
2. **قابلية الصيانة**: كود منظم في كلاس واحد
3. **إعادة الاستخدام**: يمكن استخدام الكلاس في أماكن أخرى
4. **سهولة التطوير**: واجهة برمجية واضحة
5. **معالجة الأخطاء**: معالجة شاملة للأخطاء

## متطلبات النظام

- Firebase SDK 10.14.1+
- Service Worker support
- HTTPS (للإشعارات)
- إذن الإشعارات من المستخدم

## استكشاف الأخطاء

### مشاكل شائعة
1. **Firebase غير محمل**: تأكد من تحميل Firebase SDK
2. **Service Worker فشل**: تحقق من مسار Service Worker
3. **Token غير محفوظ**: تحقق من CSRF token
4. **إذن مرفوض**: اطلب إذن الإشعارات من المستخدم

### سجلات التطوير
```javascript
// تفعيل السجلات المفصلة
console.log('حالة التطبيق:', firebaseNotification.getStatus());
```
