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

## ScreenShots

<img src="./art/index-view.png" />
<img src="./art/detail-view.png" />
<img src="./art/detail-view-1.png" />
<img src="./art/form-view.png" />
<img src="./art/form-view-1.png" />


License This package is open-sourced software licensed under the [MIT license](LICENSE).
