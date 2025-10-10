<?php

namespace Webkul\RealTimeNotification\Listeners;

use Illuminate\Support\Facades\Log;

class AdminLoginListener
{
    /**
     * Handle the admin login event.
     *
     * @param mixed $adminUser
     * @return void
     */
    public function handle($adminUser)
    {
     

        // You can add more functionality here such as:
        // - Send real-time notification to other admins
        // - Update user activity status
        // - Send welcome notification to the logged-in admin
        // - Track login statistics
    }
}
