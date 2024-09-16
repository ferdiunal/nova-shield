# Nova Shield - WIP

<img src="./art/banner.png" />

**Nova Shield** simplifies permission management for your [Laravel Nova](https://nova.laravel.com) resources using [Spatie Permission](https://github.com/spatie/laravel-permission). Easily grant or revoke access to specific resources and actions, streamlining your workflow and improving security.

## Installation

```bash
composer require ferdiunal/nova-shield
```

## Configuration

Optionally, you can publish the configuration file using this command:

```bash
php artisan vendor:publish --tag nova-shield-config
```

In the config file, you can control resource permissions by specifying the resources parameter with either the path to the Nova resources folder or a string class path. 

For example, in the code below, we define the resources and their corresponding policies:

```php
<?php

return [
    "resources" => [
        // Nova resources folder path
        app_path("Nova"),
        // Or a string class path
        \Ferdiunal\NovaShield\Http\Nova\ShieldResource::class,
        // Custom resource: For custom menu items
        // [
        //     "name" => "Custom Menu Item",
        //     "policies" => ['CustomMenuPolicy'] // Add custom menu policies here
        // ]
    ],
    "policies" => [
        // List of policies for the resources
        "viewAny",
        "view",
        "create",
        "update",
        "replicate",
        "delete",
        "restore",
        "forceDelete",
        "runAction",
        "runDestructiveAction",
        "canImpersonate",
        "canBeImpersonated",
        "add{Model}",
        "attach{Model}",
        "attachAny{Model}",
        "detach{Model}",
    ],
    "langs" => [
        // lang_path('en/nova-shield.json'),
        // base_path('langs/en/nova-shield.json'),
    ]
];

``` 

### Custom Menu Configuration

The main purpose of the package was to manage permissions for Nova Resources, but I realized there was a need for Custom Menu support as well. The necessary development has been completed. You can refer to the usage below for implementing Custom Menus.

```php
    // app/Providers/NovaServiceProvider.php
    Nova::mainMenu(function (Request $request) {
        return [
            MenuItem::make('Custom Menu Item')
                ->path('/custom-menu')
                ->canSee(
                    function ($request) {
                        return $request->user()->can('CustomMenuPolicy');
                    }
                )
        ];
    });

    // ----

    // config/nova-shield.php
    return [
        "resources" => [
            ...
            // Custom resource: For custom menu items
            [
                "name" => "Custom Menu Item",
                "prefix" => "customMenuItem::",
                "policies" => ['CustomMenuPolicy'] // Add custom menu policies here
            ]
        ]
    ];
```


Then edit **`App\Nova\Resource.php`** file as follows.

```php
<?php

namespace App\Nova;

use Ferdiunal\NovaShield\PermissionAuthorizable;
use Laravel\Nova\Resource as NovaResource;

abstract class Resource extends NovaResource
{
    use PermissionAuthorizable;
    ....
}

```

## Teams Support

I assume you have done the Teams integration for [Spatie Permission](https://spatie.be/docs/laravel-permission/v6/basic-usage/teams-permissions) and you can follow the setup below for NovaShield.

### Model Integrations

Teams feature differs in each project, please fill in the relevant method according to your project.

```php
// App\Models\User

use Ferdiunal\NovaShield\Contracts\HasShieldTeam;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasShieldTeam
{
    public function getTeamIdAttribute(): mixed
    {
        // return $this->team_id
    }
}
```

### Add Team Field

Considering that the Teams feature will differ in each project, you need to add the team field yourself.

```php
namespace App\Lib;

use Ferdiunal\NovaShield\Lib\NovaTeamHelperField;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Select;

class TeamField extends NovaTeamHelperField
{
    public static function field(): Field
    {
        return Select::make('Teams', 'team_id')
            ->options([])
            ->displayUsingLabels()
            ->searchable()
            ->sortable();
    }
}

```

### Add Middleware to Nova Routes

Add TeamMiddleware to nova routes

```php

return [

    /*
    |--------------------------------------------------------------------------
    | Nova Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every Nova route, giving you the
    | chance to add your own middleware to this stack or override any of
    | the existing middleware. Or, you can just stick with this stack.
    |
    */

    'middleware' => [
        ....
        \Ferdiunal\NovaShield\Http\Middleware\TeamMiddleware::class,
    ],
];

```

## ScreenShots

<img src="./art/index-view.png" />
<img src="./art/detail-view.png" />
<img src="./art/detail-view-1.png" />
<img src="./art/form-view.png" />
<img src="./art/form-view-1.png" />


License This package is open-sourced software licensed under the [MIT license](LICENSE).
