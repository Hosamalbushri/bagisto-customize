<?php

return [

    // Delivery Agent ACL
    [
        'key'    => 'delivery',
        'name'   => 'deliveryAgent::app.deliveryAgent.acl.title',
        'route'  => 'admin.deliveryAgents.index',
        'sort'   => 4,
        'icon'   => 'icon-list',
    ],

    [
        'key'    => 'delivery.deliveryAgent',
        'name'   => 'deliveryAgent::app.deliveryAgent.acl.delivery-agents',
        'route'  => 'admin.deliveryAgents.index',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.deliveryAgent.create',
        'name'   => 'deliveryAgent::app.deliveryAgent.acl.create',
        'route'  => 'admin.deliveryAgents.store',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.deliveryAgent.edit',
        'name'   => 'deliveryAgent::app.deliveryAgent.acl.edit',
        'route'  => 'admin.deliveryAgents.update',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.deliveryAgent.delete',
        'name'   => 'deliveryAgent::app.deliveryAgent.acl.delete',
        'route'  => 'admin.deliveryAgents.delete',
        'sort'   => 3,
    ],

    // Delivery Agent Ranges
    [
        'key'    => 'delivery.deliveryAgent.range',
        'name'   => 'deliveryAgent::app.range.acl.title',
        'route'  => 'admin.range.index',
        'sort'   => 4,
    ],
    [
        'key'    => 'delivery.deliveryAgent.range.create',
        'name'   => 'deliveryAgent::app.range.acl.create',
        'route'  => 'admin.range.store',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.deliveryAgent.range.edit',
        'name'   => 'deliveryAgent::app.range.acl.edit',
        'route'  => 'admin.range.update',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.deliveryAgent.range.delete',
        'name'   => 'deliveryAgent::app.range.acl.delete',
        'route'  => 'admin.range.delete',
        'sort'   => 3,
    ],

    // Delivery Agent Orders
    [
        'key'    => 'delivery.deliveryAgent.order',
        'name'   => 'deliveryAgent::app.orders.acl.title',
        'route'  => 'admin.deliveryAgents.order.select-delivery-agent',
        'sort'   => 5,
    ],
    [
        'key'    => 'delivery.deliveryAgent.order.assign-delivery-agent',
        'name'   => 'deliveryAgent::app.orders.acl.select-delivery',
        'route'  => 'admin.orders.assignDeliveryAgent',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.deliveryAgent.order.accept',
        'name'   => 'deliveryAgent::app.deliveryAgent.orders.acl.accept',
        'route'  => 'admin.orders.changeStatus',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.deliveryAgent.order.reject',
        'name'   => 'deliveryAgent::app.deliveryAgent.orders.acl.reject',
        'route'  => 'admin.orders.changeStatus',
        'sort'   => 3,
    ],
    [
        'key'    => 'delivery.deliveryAgent.order.out_for_delivery',
        'name'   => 'deliveryAgent::app.deliveryAgent.orders.acl.out_for_delivery',
        'route'  => 'admin.orders.changeStatus',
        'sort'   => 4,
    ],
    [
        'key'    => 'delivery.deliveryAgent.order.delivered',
        'name'   => 'deliveryAgent::app.deliveryAgent.orders.acl.delivered',
        'route'  => 'admin.orders.changeStatus',
        'sort'   => 5,
    ],

    // Country ACL
    [
        'key'    => 'delivery.countries',
        'name'   => 'deliveryAgent::app.country.acl.title',
        'route'  => 'admin.country.index',
        'sort'   => 5,
        'icon'   => 'icon-list',
    ],
    [
        'key'    => 'delivery.countries.country',
        'name'   => 'deliveryAgent::app.country.acl.countries',
        'route'  => 'admin.country.index',
        'sort'   => 1,
        'icon'   => 'icon-list',
    ],
    [
        'key'    => 'delivery.countries.country.create',
        'name'   => 'deliveryAgent::app.country.acl.create',
        'route'  => 'admin.country.store',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.countries.country.edit',
        'name'   => 'deliveryAgent::app.country.acl.edit',
        'route'  => 'admin.country.update',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.countries.country.delete',
        'name'   => 'deliveryAgent::app.country.acl.delete',
        'route'  => 'admin.country.delete',
        'sort'   => 3,
    ],

    // States ACL
    [
        'key'    => 'delivery.countries.states',
        'name'   => 'deliveryAgent::app.country.state.acl.states',
        'route'  => 'admin.states.index',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.countries.states.create',
        'name'   => 'deliveryAgent::app.country.state.acl.create',
        'route'  => 'admin.states.store',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.countries.states.edit',
        'name'   => 'deliveryAgent::app.country.state.acl.edit',
        'route'  => 'admin.states.update',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.countries.states.delete',
        'name'   => 'deliveryAgent::app.country.state.acl.delete',
        'route'  => 'admin.states.delete',
        'sort'   => 3,
    ],

    // Areas ACL
    [
        'key'    => 'delivery.countries.states.area',
        'name'   => 'deliveryAgent::app.country.state.area.acl.areas',
        'route'  => 'admin.area.index',
        'sort'   => 4,
    ],
    [
        'key'    => 'delivery.countries.states.area.create',
        'name'   => 'deliveryAgent::app.country.state.area.acl.create',
        'route'  => 'admin.area.store',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.countries.states.area.edit',
        'name'   => 'deliveryAgent::app.country.state.area.acl.edit',
        'route'  => 'admin.area.update',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.countries.states.area.delete',
        'name'   => 'deliveryAgent::app.country.state.area.acl.delete',
        'route'  => 'admin.area.delete',
        'sort'   => 3,
    ],

];
