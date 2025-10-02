<?php

namespace Webkul\RealTimeNotification\Helpers;

use Webkul\Core\Repositories\CoreConfigRepository;

class FirebaseHelper
{
    /**
     * CoreConfigRepository instance
     */
    protected $coreConfigRepository;

    /**
     * Create a new helper instance.
     */
    public function __construct(CoreConfigRepository $coreConfigRepository)
    {
        $this->coreConfigRepository = $coreConfigRepository;
    }

    /**
     * Get Firebase configuration
     */
    public function getFirebaseConfig(): array
    {
        // Default configuration if not set in admin
        $defaultConfig = [
            'apiKey'            => 'AIzaSyB4WUgl9IyTd7dm4cDy_OH-OUX6x7ZObgE',
            'authDomain'        => 'najaz-store.firebaseapp.com',
            'projectId'         => 'najaz-store',
            'storageBucket'     => 'najaz-store.firebasestorage.app',
            'messagingSenderId' => '565715806307',
            'appId'             => '1:565715806307:web:d9ebd4f473ec3ef4623056',
            'measurementId'     => 'G-H75464RFFL',
        ];

        return [
            'apiKey'            => core()->getConfigData('general.firebase.settings.api_key') ?: $defaultConfig['apiKey'],
            'authDomain'        => core()->getConfigData('general.firebase.settings.auth_domain') ?: $defaultConfig['authDomain'],
            'projectId'         => core()->getConfigData('general.firebase.settings.project_id') ?: $defaultConfig['projectId'],
            'storageBucket'     => core()->getConfigData('general.firebase.settings.storage_bucket') ?: $defaultConfig['storageBucket'],
            'messagingSenderId' => core()->getConfigData('general.firebase.settings.messaging_sender_id') ?: $defaultConfig['messagingSenderId'],
            'appId'             => core()->getConfigData('general.firebase.settings.app_id') ?: $defaultConfig['appId'],
            'measurementId'     => core()->getConfigData('general.firebase.settings.measurement_id') ?: $defaultConfig['measurementId'],
        ];
    }

    /**
     * Get Firebase VAPID key
     */
    public function getVapidKey(): ?string
    {
        return core()->getConfigData('general.firebase.settings.vapid_key');
    }

    /**
     * Get notification settings
     */
    public function getNotificationSettings(): array
    {
        return [
            'enable_notifications'  => core()->getConfigData('general.notification.settings.enable_notifications'),
            'notification_duration' => core()->getConfigData('general.notification.settings.notification_duration'),
            'default_icon'          => core()->getConfigData('general.notification.settings.default_icon'),
            'auto_close'            => core()->getConfigData('general.notification.settings.auto_close'),
        ];
    }

    /**
     * Check if notifications are enabled
     */
    public function isNotificationEnabled(): bool
    {
        return (bool) core()->getConfigData('general.notification.settings.enable_notifications');
    }

    /**
     * Get Firebase config as JSON for JavaScript
     */
    public function getFirebaseConfigJson(): string
    {
        return json_encode($this->getFirebaseConfig());
    }

    /**
     * Get notification settings as JSON for JavaScript
     */
    public function getNotificationSettingsJson(): string
    {
        return json_encode($this->getNotificationSettings());
    }
}
