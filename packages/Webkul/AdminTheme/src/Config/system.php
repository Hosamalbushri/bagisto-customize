<?php

return [
    [
        'key'    => 'general.location',
        'name'   => 'adminTheme::app.configuration.index.general.location.title',
        'info'   => 'adminTheme::app.configuration.index.general.location.info',
        'icon'   => 'map.svg',
        'sort'   => 4,
        'fields' => [],
    ],
    [
        'key'    => 'general.location.store',
        'name'   => 'adminTheme::app.configuration.index.general.location.store.title',
        'info'   => 'adminTheme::app.configuration.index.general.location.store.store-info',
        'icon'   => 'icon-store',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'default_country',
                'title'         => 'adminTheme::app.configuration.index.general.location.store.fields.default-country',
                'type'          => 'country',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ],
    [
        'key'    => 'catalog.products.create',
        'name'   => 'adminTheme::app.configuration.index.catalog.products.create.title',
        'info'   => 'adminTheme::app.configuration.index.catalog.products.create.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'enable_default_product_type',
                'title'         => 'adminTheme::app.configuration.index.catalog.products.create.enable-default-product-type',
                'info'          => 'adminTheme::app.configuration.index.catalog.products.create.enable-default-product-type-info',
                'type'          => 'boolean',
                'default'       => 0,
            ], [
                'name'          => 'default_product_type',
                'title'         => 'adminTheme::app.configuration.index.catalog.products.create.default-product-type',
                'info'          => 'adminTheme::app.configuration.index.catalog.products.create.default-product-type-info',
                'type'          => 'select',
                'default'       => 'simple',
                'depends'       => 'enable_default_product_type:1',
                'options'       => [
                    [
                        'title' => 'product::app.type.simple',
                        'value' => 'simple',
                    ], [
                        'title' => 'product::app.type.configurable',
                        'value' => 'configurable',
                    ], [
                        'title' => 'product::app.type.virtual',
                        'value' => 'virtual',
                    ], [
                        'title' => 'product::app.type.grouped',
                        'value' => 'grouped',
                    ], [
                        'title' => 'product::app.type.downloadable',
                        'value' => 'downloadable',
                    ], [
                        'title' => 'product::app.type.bundle',
                        'value' => 'bundle',
                    ],
                ],
            ], [
                'name'          => 'enable_default_attribute_family',
                'title'         => 'adminTheme::app.configuration.index.catalog.products.create.enable-default-attribute-family',
                'info'          => 'adminTheme::app.configuration.index.catalog.products.create.enable-default-attribute-family-info',
                'type'          => 'boolean',
                'default'       => 0,
            ], [
                'name'          => 'default_attribute_family_id',
                'title'         => 'adminTheme::app.configuration.index.catalog.products.create.default-attribute-family',
                'info'          => 'adminTheme::app.configuration.index.catalog.products.create.default-attribute-family-info',
                'type'          => 'select',
                'default'       => '',
                'depends'       => 'enable_default_attribute_family:1',
                'options'       => 'Webkul\AdminTheme\Helpers\AdminHelper@attribute_families',
            ],
            [
                'name'          => 'auto_generate_sku',
                'title'         => 'adminTheme::app.configuration.index.catalog.products.create.auto-generate-sku',
                'info'          => 'adminTheme::app.configuration.index.catalog.products.create.auto-generate-sku-info',
                'type'          => 'boolean',
                'default'       => 0,
            ], [
                'name'          => 'sku_prefix',
                'title'         => 'adminTheme::app.configuration.index.catalog.products.create.sku-prefix',
                'info'          => 'adminTheme::app.configuration.index.catalog.products.create.sku-prefix-info',
                'type'          => 'text',
                'default'       => '',
                'depends'       => 'auto_generate_sku:1',
            ], [
                'name'          => 'sku_length',
                'title'         => 'adminTheme::app.configuration.index.catalog.products.create.sku-length',
                'info'          => 'adminTheme::app.configuration.index.catalog.products.create.sku-length-info',
                'type'          => 'text',
                'default'       => '6',
                'depends'       => 'auto_generate_sku:1',
            ],
        ],
    ],
    [
        'key'    => 'customer.address.options',
        'name'   => 'adminTheme::app.configuration.index.customer.address.options.title',
        'info'   => 'adminTheme::app.configuration.index.customer.address.options.info',
        'icon'   => 'icon-location',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'show_company_name',
                'title'         => 'adminTheme::app.configuration.index.customer.address.options.show-company-name',
                'type'          => 'boolean',
                'default'       => 1,
                'channel_based' => true,
            ],
            [
                'name'          => 'show_tax_number',
                'title'         => 'adminTheme::app.configuration.index.customer.address.options.show-tax-number',
                'type'          => 'boolean',
                'default'       => 1,
                'channel_based' => true,
            ],
            [
                'name'          => 'show_postal_code',
                'title'         => 'adminTheme::app.configuration.index.customer.address.options.show-postal-code',
                'type'          => 'boolean',
                'default'       => 1,
                'channel_based' => true,
            ],
        ],
    ],
    [
        'key'    => 'general.api.notification_settings',
        'name'   => 'adminTheme::app.configuration.index.general.graphql.notification-settings.title',
        'info'   => 'adminTheme::app.configuration.index.general.graphql.notification-settings.info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'enable_order_status_notifications',
                'title'         => 'adminTheme::app.configuration.index.general.graphql.notification-settings.enable-order-status-notifications',
                'type'          => 'boolean',
                'channel_based' => true,
                'info'          => 'adminTheme::app.configuration.index.general.graphql.notification-settings.enable-order-status-notifications-info',
            ],
            [
                'name'          => 'enable_order_comment_notifications',
                'title'         => 'adminTheme::app.configuration.index.general.graphql.notification-settings.enable-order-comment-notifications',
                'type'          => 'boolean',
                'channel_based' => true,
                'info'          => 'adminTheme::app.configuration.index.general.graphql.notification-settings.enable-order-comment-notifications-info',
            ],
            [
                'name'          => 'enable_customer_note_notifications',
                'title'         => 'adminTheme::app.configuration.index.general.graphql.notification-settings.enable-customer-note-notifications',
                'type'          => 'boolean',
                'channel_based' => true,
                'info'          => 'adminTheme::app.configuration.index.general.graphql.notification-settings.enable-customer-note-notifications-info',
            ],
        ],
    ],
];
