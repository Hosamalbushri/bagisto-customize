window.addEventListener("firebase-notification-received",function(t){console.log("📢 تم استقبال إشعار جديد:",t.detail);const i=t.detail;i.notification;const o=e(i);a(o)});function e(t){const i=t.data||{},o=t.notification||{};return{order_id:i.order_id||"غير محدد",order_status:i.order_status||"غير محدد",customer_name:i.customer_name||"عميل غير محدد",order_total:i.order_total||"0",title:o.title||"طلب جديد",body:o.body||"تم استلام طلب جديد",icon:o.icon||"/favicon.ico",click_action:i.click_action||"#"}}function a(t){r();const i=document.createElement("div");i.id="realtime-order-notification",i.className="realtime-notification",i.innerHTML=`
        <div class="notification-header">
            <span class="notification-icon">🔔</span>
            <span class="notification-title">${t.title}</span>
            <button class="notification-close" onclick="closeNotification()">×</button>
        </div>
        <div class="notification-body">
            <p><strong>رقم الطلب:</strong> #${t.order_id}</p>
            <p><strong>العميل:</strong> ${t.customer_name}</p>
            <p><strong>حالة الطلب:</strong> ${t.order_status}</p>
            <p><strong>المبلغ:</strong> ${t.order_total} ريال</p>
        </div>
        <div class="notification-actions">
            <button class="btn-primary" onclick="viewOrder(${t.order_id})">
                عرض الطلب
            </button>
            <button class="btn-secondary" onclick="closeNotification()">
                إغلاق
            </button>
        </div>
    `,c(),document.body.appendChild(i),d(),setTimeout(()=>{i.parentNode&&i.remove()},1e4)}function r(){const t=document.getElementById("realtime-order-notification");t&&t.remove()}function c(){if(document.getElementById("realtime-notification-styles"))return;const t=document.createElement("style");t.id="realtime-notification-styles",t.textContent=`
        .realtime-notification {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            max-width: 350px;
            z-index: 10000;
            font-family: Arial, sans-serif;
            direction: rtl;
            animation: slideInLeft 0.3s ease-out;
        }

        .notification-header {
            background: #007bff;
            color: white;
            padding: 12px 15px;
            border-radius: 8px 8px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .notification-icon {
            font-size: 18px;
        }

        .notification-title {
            font-weight: bold;
            flex: 1;
            margin: 0 10px;
        }

        .notification-close {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 0;
            width: 25px;
            height: 25px;
        }

        .notification-body {
            padding: 15px;
            background: #f8f9fa;
        }

        .notification-body p {
            margin: 5px 0;
            font-size: 14px;
        }

        .notification-actions {
            padding: 10px 15px;
            background: white;
            border-radius: 0 0 8px 8px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-primary {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `,document.head.appendChild(t)}function d(){try{const t=new(window.AudioContext||window.webkitAudioContext),i=t.createOscillator(),o=t.createGain();i.connect(o),o.connect(t.destination),i.frequency.value=800,o.gain.setValueAtTime(.1,t.currentTime),o.gain.exponentialRampToValueAtTime(.01,t.currentTime+.5),i.start(t.currentTime),i.stop(t.currentTime+.5)}catch(t){console.log("تعذر تشغيل صوت الإشعار:",t)}}function s(t){console.log("عرض تفاصيل الطلب:",t),n(),window.open("/admin/sales/orders/"+t,"_blank","width=800,height=600")}function n(){const t=document.getElementById("realtime-order-notification");t&&t.remove()}window.closeNotification=n;window.viewOrder=s;
