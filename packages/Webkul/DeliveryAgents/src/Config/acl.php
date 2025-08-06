<?php

return [

    //     Delivery Agent ACL

    [
        'key'    => 'delivery',
        'name'   => 'deliveryagent::app.deliveryagents.acl.title',
        'route'  => 'admin.deliveryagents.index',
        'sort'   => -5,
        'icon'   => 'icon-list',
    ],
    [
        'key'    => 'delivery.deliveryAgent',
        'name'   => 'deliveryagent::app.deliveryagents.acl.delivery-agents',
        'route'  => 'admin.deliveryagents.view',
        'sort'   => 2,
    ],

    [
        'key'    => 'delivery.deliveryAgent.view-details',
        'name'   => 'deliveryagent::app.deliveryagents.acl.view-details',
        'route'  => 'admin.deliveryagents.view',
        'sort'   => 2,
    ],
    [
        'key'    => 'delivery.deliveryAgent.create',
        'name'   => 'deliveryagent::app.deliveryagents.acl.create',
        'route'  => 'admin.deliveryagents.create',
        'sort'   => 3,
    ],
    [
        'key'    => 'delivery.deliveryAgent.edit',
        'name'   => 'deliveryagent::app.deliveryagents.acl.edit',
        'route'  => 'admin.deliveryagents.edit',
        'sort'   => 4,
    ],
    [
        'key'    => 'delivery.deliveryAgent.delete',
        'name'   => 'deliveryagent::app.deliveryagents.acl.delete',
        'route'  => 'admin.deliveryagents.delete',
        'sort'   => 5,
    ],
    //     Country ACL

    [
        'key'    => 'countries',
        'name'   => 'deliveryagent::app.country.acl.title',
        'route'  => 'admin.country.index',
        'sort'   => 6,
        'icon'   => 'icon-list',
    ],
    [
        'key'    => 'countries.country',
        'name'   => 'deliveryagent::app.country.acl.countries',
        'route'  => 'admin.country.index',
        'sort'   => 6,
        'icon'   => 'icon-list',
    ],
    [
        'key'    => 'countries.country.create',
        'name'   => 'deliveryagent::app.country.acl.create',
        'route'  => 'admin.country.create',
        'sort'   => 6,
    ],
    [
        'key'    => 'countries.country.edit',
        'name'   => 'deliveryagent::app.country.acl.edit',
        'route'  => 'admin.country.edit',
        'sort'   => 6,
    ],
    [
        'key'    => 'countries.country.delete',
        'name'   => 'deliveryagent::app.country.acl.delete',
        'route'  => 'admin.country.delete',
        'sort'   => 6,
    ],

    //     States ACL

    [
        'key'    => 'countries.states',
        'name'   => 'deliveryagent::app.country.state.acl.states',
        'route'  => 'admin.states.index',
        'sort'   => 6,
    ],
    [
        'key'    => 'countries.states.create',
        'name'   => 'deliveryagent::app.country.state.acl.create',
        'route'  => 'admin.states.create',
        'sort'   => 6,
    ],
    [
        'key'    => 'countries.states.edit',
        'name'   => 'deliveryagent::app.country.state.acl.edit',
        'route'  => 'admin.states.edit',
        'sort'   => 6,
    ],
    [
        'key'    => 'countries.states.delete',
        'name'   => 'deliveryagent::app.country.state.acl.delete',
        'route'  => 'admin.states.delete',
        'sort'   => 6,
    ],

];
