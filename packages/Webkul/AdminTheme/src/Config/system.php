<?php
return[
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
                'options'       => 'Webkul\AdminTheme\Helpers\AttributeFamilyOptions@attribute_families',
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
        ]
    ]
];
