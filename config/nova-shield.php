<?php

return [
    'resources' => [
        app_path('Nova'),
        \Ferdiunal\NovaShield\Http\Nova\ShieldResource::class,
        // Custom resource: For custom menu items
        // [
        //     "name" => "Custom Menu Item",
        //     "prefix" => "customMenuItem::",
        //     "policies" => ["CustomMenuPolicy"] // Add custom menu policies here
        // ]
    ],
    // 'teamFields' => \App\Lib\TeamField::class,

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

    'langs' => [
        // lang_path('en/nova-shield.json'),
        // base_path('langs/en/nova-shield.json'),
    ],
];
