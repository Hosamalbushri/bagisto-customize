<?php

return [

    //     Delivery Agent ACL

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
        'route'  => 'admin.deliveryagents.create',
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

    //  DeliveryAgent Rangs
    [
        'key'    => 'delivery.deliveryAgent.range',
        'name'   => 'deliveryagent::app.range.acl.title',
        'route'  => 'admin.range.index',
        'sort'   => 4,
    ],
    [
        'key'    => 'delivery.deliveryAgent.range.create',
        'name'   => 'deliveryagent::app.range.acl.create',
        'route'  => 'admin.range.create',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.deliveryAgent.range.edit',
        'name'   => 'deliveryagent::app.range.acl.edit',
        'route'  => 'admin.range.edit',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.deliveryAgent.range.delete',
        'name'   => 'deliveryagent::app.range.acl.delete',
        'route'  => 'admin.range.delete',
        'sort'   => 3,
    ],
    // Delivery Agent Orders acl
    [
        'key'    => 'delivery.deliveryAgent.order',
        'name'   => 'deliveryagent::app.orders.acl.title',
        'route'  => 'admin.deliveryagents.order.select-delivery-agent',
        'sort'   => 5,
    ],
    [
        'key'    => 'delivery.deliveryAgent.order.select-delivery-agent',
        'name'   => 'deliveryagent::app.orders.acl.select-delivery',
        'route'  => 'admin.deliveryagents.order.select-delivery-agent',
        'sort'   => 1,
    ],

    //     Country ACL

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
        'route'  => 'admin.country.create',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.countries.country.edit',
        'name'   => 'deliveryagent::app.country.acl.edit',
        'route'  => 'admin.country.edit',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.countries.country.delete',
        'name'   => 'deliveryagent::app.country.acl.delete',
        'route'  => 'admin.country.delete',
        'sort'   => 3,
    ],

    //     States ACL

    [
        'key'    => 'delivery.countries.states',
        'name'   => 'deliveryagent::app.country.state.acl.states',
        'route'  => 'admin.states.index',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.countries.states.create',
        'name'   => 'deliveryagent::app.country.state.acl.create',
        'route'  => 'admin.states.create',
        'sort'   => 1,
    ],
    [
        'key'    => 'delivery.countries.states.edit',
        'name'   => 'deliveryagent::app.country.state.acl.edit',
        'route'  => 'admin.states.edit',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.countries.states.delete',
        'name'   => 'deliveryagent::app.country.state.acl.delete',
        'route'  => 'admin.states.delete',
        'sort'   => 3,
    ],



];
