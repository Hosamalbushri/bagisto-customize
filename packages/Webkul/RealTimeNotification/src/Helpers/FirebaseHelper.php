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

        // Get the JSON configuration from the single field
        $webProjectConfig = core()->getConfigData('general.firebase.settings.web_project_config');

        if ($webProjectConfig) {
            try {
                $config = json_decode($webProjectConfig, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($config)) {
                    return array_merge($defaultConfig, $config);
                }
            } catch (\Exception $e) {
                // If JSON parsing fails, return default config
            }
        }

        return $defaultConfig;
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
