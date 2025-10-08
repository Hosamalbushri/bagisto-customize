<?php

return [
    // Only show Firebase settings if GraphQL package is available
    ...(class_exists('Webkul\GraphQLAPI\Providers\GraphQLAPIServiceProvider') ? [
        [
            'key'  => 'general.firebase',
            'name' => 'realtimenotification::app.configuration.firebase.title',
            'info' => 'realtimenotification::app.configuration.firebase.info',
            'icon' => 'settings/settings.svg',
            'sort' => 3,
        ],
    ] : []),
    // Only show Firebase settings if GraphQL package is available
    ...(class_exists('Webkul\GraphQLAPI\Providers\GraphQLAPIServiceProvider') ? [
        [
            'key'    => 'general.firebase.settings',
            'name'   => 'realtimenotification::app.configuration.firebase.title',
            'info'   => 'realtimenotification::app.configuration.firebase.info',
            'icon'   => 'icon-notification',
            'sort'   => 1,
            'fields' => [
                [
                    'name'          => 'web_project_config',
                    'title'         => 'realtimenotification::app.configuration.firebase.web_project_config',
                    'info'          => 'realtimenotification::app.configuration.firebase.web_project_config_info',
                    'type'          => 'textarea',
                    'validation'    => 'required',
                    'channel_based' => true,
                ],
                [
                    'name'          => 'vapid_key',
                    'title'         => 'realtimenotification::app.configuration.firebase.vapid_key',
                    'info'          => 'realtimenotification::app.configuration.firebase.vapid_key_info',
                    'type'          => 'text',
                    'validation'    => 'required',
                    'channel_based' => true,
                ],
            ],
        ],
    ] : []),
    // Only show notification settings if GraphQL package is available
    ...(class_exists('Webkul\GraphQLAPI\Providers\GraphQLAPIServiceProvider') ? [
        [
            'key'    => 'general.firebase.notification',
            'name'   => 'realtimenotification::app.configuration.settings.title',
            'info'   => 'realtimenotification::app.configuration.settings.info',
            'sort'   => 2,
            'fields' => [
                [
                    'name'          => 'enable_notifications',
                    'title'         => 'realtimenotification::app.configuration.settings.enable_notifications',
                    'info'          => 'realtimenotification::app.configuration.settings.enable_notifications_info',
                    'type'          => 'boolean',
                    'validation'    => '',
                    'channel_based' => true,
                ],
                [
                    'name'          => 'notification_duration',
                    'title'         => 'realtimenotification::app.configuration.settings.notification_duration',
                    'info'          => 'realtimenotification::app.configuration.settings.notification_duration_info',
                    'type'          => 'text',
                    'validation'    => 'numeric',
                    'depends'       => 'enable_notifications:1',
                    'channel_based' => true,
                ],
                [
                    'name'          => 'default_icon',
                    'title'         => 'realtimenotification::app.configuration.settings.default_icon',
                    'info'          => 'realtimenotification::app.configuration.settings.default_icon_info',
                    'type'          => 'image',
                    'channel_based' => false,
                    'validation'    => 'mimes:bmp,jpeg,jpg,png,webp,svg,ico',
                    'depends'       => 'enable_notifications:1',
                ],
                [
                    'name'          => 'auto_close',
                    'title'         => 'realtimenotification::app.configuration.settings.auto_close',
                    'info'          => 'realtimenotification::app.configuration.settings.auto_close_info',
                    'type'          => 'boolean',
                    'validation'    => '',
                    'depends'       => 'enable_notifications:1',
                    'channel_based' => true,
                ],
            ],
        ],
    ] : []),
];
