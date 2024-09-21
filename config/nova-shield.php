<?php

return [
    /**
     * Specify the resources that will be used in the project.
     * If you want to use custom resources, you can add them to the list.
     */
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

    /**
     * Constant policies of Laravel Nova
     */
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

    /**
     * Specify the file path of each language files for authorisations.
     */
    'langs' => [
        // lang_path('en/nova-shield.json'),
        // base_path('langs/en/nova-shield.json'),
    ],

    /**
     * Default Super admin role name and guard
     */
    'superAdmin' => [
        'name' => 'super-admin',
        'guard' => 'web',
    ],

    'hooks' => [
        /**
         * When matching permissions with roles, upsert is used by default.
         * If you are using custom ID types like UUID or ULID, you need to include them in the upsert operation.
         * Therefore, you can write and use a query that suits your project needs.
         */
        'permission' => \Ferdiunal\NovaShield\Lib\DefaultPermissionHook::class,
    ],
];
