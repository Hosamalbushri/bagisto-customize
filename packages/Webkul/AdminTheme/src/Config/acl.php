<?php

return [

    // Country ACL
    [
        'key'    => 'countries',
        'name'   => 'adminTheme::app.country.acl.title',
        'route'  => 'admin.country.index',
        'sort'   => 10,
    ],
    [
        'key'    => 'countries.country',
        'name'   => 'adminTheme::app.country.acl.countries',
        'route'  => 'admin.country.index',
        'sort'   => 1,
        'icon'   => 'icon-list',
    ],
    [
        'key'    => 'countries.country.create',
        'name'   => 'adminTheme::app.country.acl.create',
        'route'  => 'admin.country.store',
        'sort'   => 1,
    ],
    [
        'key'    => 'countries.country.edit',
        'name'   => 'adminTheme::app.country.acl.edit',
        'route'  => 'admin.country.update',
        'sort'   => 2,
    ],
    [
        'key'    => 'countries.country.delete',
        'name'   => 'adminTheme::app.country.acl.delete',
        'route'  => 'admin.country.delete',
        'sort'   => 3,
    ],

    // States ACL
    [
        'key'    => 'countries.states',
        'name'   => 'adminTheme::app.country.state.acl.states',
        'route'  => 'admin.states.index',
        'sort'   => 2,
    ],
    [
        'key'    => 'countries.states.create',
        'name'   => 'adminTheme::app.country.state.acl.create',
        'route'  => 'admin.states.store',
        'sort'   => 1,
    ],
    [
        'key'    => 'countries.states.edit',
        'name'   => 'adminTheme::app.country.state.acl.edit',
        'route'  => 'admin.states.update',
        'sort'   => 2,
    ],
    [
        'key'    => 'countries.states.delete',
        'name'   => 'adminTheme::app.country.state.acl.delete',
        'route'  => 'admin.states.delete',
        'sort'   => 3,
    ],

    // Areas ACL
    [
        'key'    => 'countries.states.area',
        'name'   => 'adminTheme::app.country.state.area.acl.areas',
        'route'  => 'admin.area.index',
        'sort'   => 3,
    ],
    [
        'key'    => 'countries.states.area.create',
        'name'   => 'adminTheme::app.country.state.area.acl.create',
        'route'  => 'admin.area.store',
        'sort'   => 1,
    ],
    [
        'key'    => 'countries.states.area.edit',
        'name'   => 'adminTheme::app.country.state.area.acl.edit',
        'route'  => 'admin.area.update',
        'sort'   => 2,
    ],
    [
        'key'    => 'countries.states.area.delete',
        'name'   => 'adminTheme::app.country.state.area.acl.delete',
        'route'  => 'admin.area.delete',
        'sort'   => 3,
    ],

];
