<?php

return [

    // Delivery Agent ACL
    [
        'key'    => 'delivery',
        'name'   => 'deliveryagent::app.deliveryagents.acl.title',
        'route'  => 'admin.deliveryagents.index',
        'sort'   => 4,
        'icon'   => 'icon-list',
    ],

    [
        'key'    => 'delivery.deliveryAgent',
        'name'   => 'deliveryagent::app.deliveryagents.acl.delivery-agents',
        'route'  => 'admin.deliveryagents.index',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.deliveryAgent.create',
        'name'   => 'deliveryagent::app.deliveryagents.acl.create',
        'route'  => 'admin.deliveryagents.store',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.deliveryAgent.edit',
        'name'   => 'deliveryagent::app.deliveryagents.acl.edit',
        'route'  => 'admin.deliveryagents.update',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.deliveryAgent.delete',
        'name'   => 'deliveryagent::app.deliveryagents.acl.delete',
        'route'  => 'admin.deliveryagents.delete',
        'sort'   => 3,
    ],

    // Delivery Agent Ranges
    [
        'key'    => 'delivery.deliveryAgent.range',
        'name'   => 'deliveryagent::app.range.acl.title',
        'route'  => 'admin.range.index',
        'sort'   => 4,
    ],
    [
        'key'    => 'delivery.deliveryAgent.range.create',
        'name'   => 'deliveryagent::app.range.acl.create',
        'route'  => 'admin.range.store',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.deliveryAgent.range.edit',
        'name'   => 'deliveryagent::app.range.acl.edit',
        'route'  => 'admin.range.update',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.deliveryAgent.range.delete',
        'name'   => 'deliveryagent::app.range.acl.delete',
        'route'  => 'admin.range.delete',
        'sort'   => 3,
    ],

    // Delivery Agent Orders
    [
        'key'    => 'delivery.deliveryAgent.order',
        'name'   => 'deliveryagent::app.orders.acl.title',
        'route'  => 'admin.deliveryagents.order.select-delivery-agent',
        'sort'   => 5,
    ],
    [
        'key'    => 'delivery.deliveryAgent.order.assign-delivery-agent',
        'name'   => 'deliveryagent::app.orders.acl.select-delivery',
        'route'  => 'admin.orders.assignDeliveryAgent',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.deliveryAgent.order.accept',
        'name'   => 'deliveryagent::app.deliveryagents.orders.acl.accept',
        'route'  => 'admin.orders.changeStatus',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.deliveryAgent.order.reject',
        'name'   => 'deliveryagent::app.deliveryagents.orders.acl.reject',
        'route'  => 'admin.orders.changeStatus',
        'sort'   => 3,
    ],
    [
        'key'    => 'delivery.deliveryAgent.order.out_for_delivery',
        'name'   => 'deliveryagent::app.deliveryagents.orders.acl.out_for_delivery',
        'route'  => 'admin.orders.changeStatus',
        'sort'   => 4,
    ],
    [
        'key'    => 'delivery.deliveryAgent.order.delivered',
        'name'   => 'deliveryagent::app.deliveryagents.orders.acl.delivered',
        'route'  => 'admin.orders.changeStatus',
        'sort'   => 5,
    ],

    // Country ACL
    [
        'key'    => 'delivery.countries',
        'name'   => 'deliveryagent::app.country.acl.title',
        'route'  => 'admin.country.index',
        'sort'   => 5,
        'icon'   => 'icon-list',
    ],
    [
        'key'    => 'delivery.countries.country',
        'name'   => 'deliveryagent::app.country.acl.countries',
        'route'  => 'admin.country.index',
        'sort'   => 1,
        'icon'   => 'icon-list',
    ],
    [
        'key'    => 'delivery.countries.country.create',
        'name'   => 'deliveryagent::app.country.acl.create',
        'route'  => 'admin.country.store',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.countries.country.edit',
        'name'   => 'deliveryagent::app.country.acl.edit',
        'route'  => 'admin.country.update',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.countries.country.delete',
        'name'   => 'deliveryagent::app.country.acl.delete',
        'route'  => 'admin.country.delete',
        'sort'   => 3,
    ],

    // States ACL
    [
        'key'    => 'delivery.countries.states',
        'name'   => 'deliveryagent::app.country.state.acl.states',
        'route'  => 'admin.states.index',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.countries.states.create',
        'name'   => 'deliveryagent::app.country.state.acl.create',
        'route'  => 'admin.states.store',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.countries.states.edit',
        'name'   => 'deliveryagent::app.country.state.acl.edit',
        'route'  => 'admin.states.update',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.countries.states.delete',
        'name'   => 'deliveryagent::app.country.state.acl.delete',
        'route'  => 'admin.states.delete',
        'sort'   => 3,
    ],

    // Areas ACL
    [
        'key'    => 'delivery.countries.states.area',
        'name'   => 'deliveryagent::app.country.state.area.acl.areas',
        'route'  => 'admin.area.index',
        'sort'   => 4,
    ],
    [
        'key'    => 'delivery.countries.states.area.create',
        'name'   => 'deliveryagent::app.country.state.area.acl.create',
        'route'  => 'admin.area.store',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.countries.states.area.edit',
        'name'   => 'deliveryagent::app.country.state.area.acl.edit',
        'route'  => 'admin.area.update',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.countries.states.area.delete',
        'name'   => 'deliveryagent::app.country.state.area.acl.delete',
        'route'  => 'admin.area.delete',
        'sort'   => 3,
    ],

];
