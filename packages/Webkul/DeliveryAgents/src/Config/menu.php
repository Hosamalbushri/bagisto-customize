<?php

return [
    [
        'key'   => 'delivery',
        'name'  => 'deliveryagent::app.deliveryagents.menu.title',
        'route' => 'admin.deliveryagents.index',
        'sort'  => 4,
        'icon'  => 'acma-icon-truck1',

    ],

    [
        'key'        => 'delivery.deliveryAgent',
        'name'       => 'deliveryagent::app.deliveryagents.menu.delivery-agents',
        'route'      => 'admin.deliveryagents.index',
        'sort'       => 1,
        'icon'       => '',
    ],


    // country menu
    [
        'key'   => 'delivery.countries',
        'name'  => 'deliveryagent::app.country.menu.title',
        'route' => 'admin.country.index',
        'sort'  => 2,
        'icon'       => '',
    ],


];
