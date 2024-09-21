<?php

namespace Ferdiunal\NovaShield\Contracts;

use Spatie\Permission\Contracts\Role;

interface SyncPermissionHook
{
    /**
     * Sync permissions to a role
     *
     * @param  \Spatie\Permission\Contracts\Role  $role
     * @param  array<int, string>  $permissions
     * @return void
     */
    public function __invoke(Role $role, array $permissions);
}
