<?php

return [
    'resources' => [
        app_path('Nova'),
        \Ferdiunal\NovaShield\Http\Nova\ShieldResource::class,
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
