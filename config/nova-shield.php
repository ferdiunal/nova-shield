<?php

return [
    'resources' => [
        app_path('Nova'),
        \Ferdiunal\NovaShield\Http\Nova\ShieldResource::class,
        // Custom resource: For custom menu items
        // [
        //     "name" => "Custom Menu Item",
        //     "policies" => ["CustomMenuPolicy"] // Add custom menu policies here
        // ]
    ],
    'policies' => [
        'viewAny',
        'view',
        'create',
        'update',
        'replicate',
        'delete',
        'restore',
        'forceDelete',
        'runAction',
        'runDestructiveAction',
        'canImpersonate',
        'canBeImpersonated',
        'add{Model}',
        'attach{Model}',
        'attachAny{Model}',
        'detach{Model}',
    ],
];
