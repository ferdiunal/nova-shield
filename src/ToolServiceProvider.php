<?php

namespace Ferdiunal\NovaShield;

use Ferdiunal\NovaShield\Http\Middleware\Authorize;
use Ferdiunal\NovaShield\Http\Nova\ShieldResource;
use Ferdiunal\NovaShield\Lib\NovaResources;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Nova;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->configuration();
            $this->routes();
            /**
             * @var \Spatie\Permission\Models\Role $roleModel
             * @var \Spatie\Permission\Models\Permission $permissionModel
             */
            $roleModel = config('permission.models.role');
            $permissionModel = config('permission.models.permission');

            if (class_exists($roleModel)) {
                $roleModel::saving(function ($role) {
                    $permissions = $role['permissions'] ?? [];
                    unset($role['permissions']);
                    Context::add('permissions', $permissions);
                });

                $roleModel::saved(function ($role) use (&$permissionModel) {
                    $permissions = Context::get('permissions', []);
                    if ($permissions) {
                        $role->syncPermissions(
                            array_map(
                                fn ($permission) => $permissionModel::firstOrCreate([
                                    'name' => $permission,
                                    'guard_name' => $role->guard_name,
                                ]),
                                $permissions
                            )
                        );
                        Context::flush();
                    }
                });
            }
        });

        Nova::serving(function (ServingNova $event) {
            ShieldResource::$model = config('permission.models.role');
            $this->translations();
            Nova::resources([
                ShieldResource::class,
            ]);
        });
    }

    protected function translations()
    {
        $langs = $this->app['config']->get('nova-shield.langs', []);
        $locale = $this->app->getLocale();

        foreach ($langs as $lang) {
            if (File::exists($lang) && File::isFile($lang) && File::extension($lang) === 'json' && str($lang)->contains("/$locale/")) {
                Nova::translations($lang);
            }
        }
    }

    public function configuration()
    {
        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__.'/../config/nova-shield.php', 'nova-shield');
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/nova-shield.php' => config_path('nova-shield.php'),
            ], 'nova-shield-config');
        }
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Nova::router(['nova', Authenticate::class, Authorize::class], 'nova-shield')
            ->group(__DIR__.'/../routes/inertia.php');

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/nova-shield')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(NovaResources::class, fn ($app) => new NovaResources(
            resourcesPath: $app['config']->get('nova-shield.resources', [
                app_path('Nova'),
            ])
        ));
    }
}
