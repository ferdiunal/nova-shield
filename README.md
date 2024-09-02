# Nova Shield - WIP

<img src="./art/banner.png" />

**Nova Shield** simplifies permission management for your [Laravel Nova](https://nova.laravel.com) resources using [Spatie Permission](https://github.com/spatie/laravel-permission). Easily grant or revoke access to specific resources and actions, streamlining your workflow and improving security.

## Installation

```bash
composer require ferdiunal/nova-shield
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
