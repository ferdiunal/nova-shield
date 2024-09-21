<?php

namespace Ferdiunal\NovaShield\Lib;

use App\Models\Permission;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\LazyCollection;

class SuperAdmin
{
    /**
     * Get the user model
     *
     * @return \App\Models\User|\Illuminate\Foundation\Auth\User
     *
     * @throws BindingResolutionException
     * @throws Exception
     */
    public static function userModel()
    {
        $guard = config('auth.defaults.guard');
        $provider = config("auth.guards.{$guard}.provider");
        $model = config("auth.providers.{$provider}.model");

        if (! class_exists($model)) {
            throw new \Exception('User model not found');
        }

        return $model;
    }

    /**
     * Get the Spatie Role model
     *
     * @return \Spatie\Permission\Models\Role
     *
     * @throws BindingResolutionException
     */
    public static function roleModel()
    {
        return config('permission.models.role');
    }

    /**
     * Get the Spatie Permission model
     *
     * @return \Spatie\Permission\Models\Permission
     *
     * @throws BindingResolutionException
     */
    public static function permissionModel()
    {
        return config('permission.models.permission');
    }

    /**
     * Get the super admin role name
     *
     * @return array
     *
     * @throws BindingResolutionException
     */
    public static function defaults()
    {
        $name = config('nova-shield.superAdmin.name', 'super-admin');
        $guard = config('nova-shield.superAdmin.guard', 'web');

        return [$name, $guard];
    }

    public function getSuperAdminRole()
    {
        [$name, $guard] = self::defaults();

        return self::roleModel()::query()->whereName($name)->whereGuardName($guard)->first();
    }

    /**
     * Create the super admin role
     *
     * @return \Spatie\Permission\Contracts\Role
     */
    public function makeSuperAdminRole()
    {
        [$name, $guard] = self::defaults();
        $role = $this->getSuperAdminRole();
        if (! $role) {
            self::roleModel()::query()->createQuietly([
                'name' => $name,
                'guard_name' => $guard,
            ]);

            $role = $this->getSuperAdminRole();
        }

        return $role;
    }

    /**
     * Get permissions from Nova resources
     *
     * @return array<int, string>
     *
     * @throws BindingResolutionException
     */
    public static function permissions()
    {
        return LazyCollection::make(
            app(NovaResources::class)->resources
        )
            ->pluck('policies')
            ->flatten()
            ->unique()
            ->toArray();
    }

    /**
     * Sync permissions to the super admin role
     *
     * @return \Spatie\Permission\Contracts\Role
     */
    public function syncRoleAndPermissions()
    {
        $role = $this->makeSuperAdminRole();

        /**
         * @var \Ferdiunal\NovaShield\Contracts\SyncPermissionHook $hook
         */
        $hook = config('nova-shield.hooks.permission', DefaultPermissionHook::class);

        $_hook = new $hook;

        $_hook($role, self::permissions());

        return $role;
    }
}
