<?php

return [
    [
        'key'   => 'delivery',
        'name'  => 'deliveryAgent::app.deliveryAgent.menu.title',
        'route' => 'admin.deliveryAgents.index',
        'sort'  => 4,
        'icon'  => 'acma-icon-truck1',

    ],

    [
        'key'        => 'delivery.deliveryAgent',
        'name'       => 'deliveryAgent::app.deliveryAgent.menu.delivery-agents',
        'route'      => 'admin.deliveryAgents.index',
        'sort'       => 1,
        'icon'       => '',
    ],

    // country menu
    [
        'key'        => 'delivery.countries',
        'name'       => 'deliveryAgent::app.country.menu.title',
        'route'      => 'admin.country.index',
        'sort'       => 2,
        'icon'       => '',
    ],

];
