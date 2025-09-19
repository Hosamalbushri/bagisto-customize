# GraphQL API للمندوبين - Delivery Agents GraphQL API

هذا الملف يوثق GraphQL API الخاص بمناديب التوصيل في نظام Bagisto.

## المميزات المتاحة

### 1. إدارة الطلبات (Orders Management)

#### Queries
- `deliveryAgentOrders` - استرجاع قائمة طلبات المندوب
- `deliveryAgentOrder` - استرجاع طلب محدد
- `deliveryAgentStats` - إحصائيات المندوب

#### Mutations
- `acceptOrder` - قبول طلب
- `rejectOrder` - رفض طلب
- `updateOrderStatus` - تحديث حالة الطلب
- `completeOrder` - إكمال الطلب

### 2. إدارة التقييمات (Reviews Management)

#### Queries
- `deliveryAgentReviews` - استرجاع تقييمات المندوب
- `deliveryAgentReview` - استرجاع تقييم محدد

#### Mutations
- `createReview` - إنشاء تقييم جديد
- `updateReview` - تحديث تقييم موجود
- `deleteReview` - حذف تقييم

## أمثلة على الاستخدام

### 1. تسجيل الدخول

```graphql
mutation {
  loginDeliveryAgent(
    email: "agent@example.com"
    password: "password"
    deviceToken: "device_token"
    deviceName: "Mobile App"
  ) {
    success
    message
    accessToken
    deliveryAgent {
      id
      firstName
      lastName
      email
    }
  }
}
```

### 2. استرجاع طلبات المندوب

```graphql
query {
  deliveryAgentOrders(
    first: 10
    page: 1
    status: "assigned_to_agent"
    orderBy: "created_at"
    sort: "desc"
  ) {
    id
    orderId
    status
    statusLabel
    assignedAt
    order {
      id
      incrementId
      customerFirstName
      customerLastName
      customerEmail
      customerPhone
    }
    canAccept
    canReject
    canComplete
  }
}
```

### 3. قبول طلب

```graphql
mutation {
  acceptOrder(input: {
    orderId: "123"
  }) {
    success
    message
    order {
      id
      status
      statusLabel
      acceptedAt
    }
  }
}
```

### 4. رفض طلب

```graphql
mutation {
  rejectOrder(input: {
    orderId: "123"
    reason: "Outside delivery area"
  }) {
    success
    message
    order {
      id
      status
      statusLabel
      rejectedAt
    }
  }
}
```

### 5. تحديث حالة الطلب

```graphql
mutation {
  updateOrderStatus(input: {
    orderId: "123"
    status: "out_for_delivery"
    notes: "Left warehouse, heading to customer"
  }) {
    success
    message
    order {
      id
      status
      statusLabel
    }
  }
}
```

### 6. إكمال الطلب

```graphql
mutation {
  completeOrder(input: {
    orderId: "123"
    deliveryNotes: "Delivered successfully"
    customerSignature: "base64_signature_data"
    deliveryPhoto: "photo_file"
  }) {
    success
    message
    order {
      id
      status
      statusLabel
      completedAt
    }
  }
}
```

### 7. استرجاع إحصائيات المندوب

```graphql
query {
  deliveryAgentStats {
    totalOrders
    completedOrders
    pendingOrders
    rejectedOrders
    averageRating
    totalReviews
    thisMonthOrders
    thisWeekOrders
    todayOrders
  }
}
```

### 8. استرجاع تقييمات المندوب

```graphql
query {
  deliveryAgentReviews(
    first: 10
    page: 1
    status: "approved"
    orderBy: "created_at"
    sort: "desc"
  ) {
    id
    rating
    comment
    status
    statusLabel
    createdAt
    order {
      id
      incrementId
    }
    customer {
      id
      firstName
      lastName
    }
  }
}
```

### 9. إنشاء تقييم

```graphql
mutation {
  createReview(input: {
    orderId: "123"
    rating: 5
    comment: "Excellent service!"
  }) {
    success
    message
    review {
      id
      rating
      comment
      status
      createdAt
    }
  }
}
```

### 10. تحديث تقييم

```graphql
mutation {
  updateReview(input: {
    id: "456"
    rating: 4
    comment: "Good service, but could be faster"
  }) {
    success
    message
    review {
      id
      rating
      comment
      status
    }
  }
}
```

## حالات الطلبات (Order Statuses)

- `assigned_to_agent` - تم تعيين المندوب
- `accepted_by_agent` - تم قبول الطلب
- `rejected_by_agent` - تم رفض الطلب
- `out_for_delivery` - جاري التوصيل
- `delivered` - تم التوصيل

## حالات التقييمات (Review Statuses)

- `pending` - معلق
- `approved` - معتمد
- `disapproved` - غير معتمد

## المصادقة (Authentication)

جميع الطلبات تتطلب مصادقة باستخدام Bearer Token:

```
Authorization: Bearer YOUR_ACCESS_TOKEN
```

## الأخطاء الشائعة

### 401 Unauthorized
- تأكد من صحة الـ access token
- تأكد من أن المندوب مفعل

### 404 Not Found
- تأكد من صحة معرف الطلب أو التقييم
- تأكد من أن الطلب مخصص لهذا المندوب

### 422 Validation Error
- تأكد من صحة البيانات المرسلة
- تأكد من أن التقييم بين 1 و 5

## ملاحظات مهمة

1. يمكن للمندوب قبول أو رفض الطلبات فقط في حالة `assigned_to_agent`
2. يمكن تحديث حالة الطلب فقط حسب التسلسل الصحيح للحالات
3. يمكن تحديث أو حذف التقييمات فقط في حالة `pending`
4. جميع التواريخ تُرجع بصيغة ISO 8601
5. الصور تُرجع كـ base64 encoded strings

## الدعم

للمساعدة أو الاستفسارات، يرجى التواصل مع فريق التطوير.
