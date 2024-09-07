<?php

namespace Ferdiunal\NovaShield;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\DestructiveAction;
use Laravel\Nova\Contracts\ImpersonatesUsers;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

trait PermissionAuthorizable
{
    public static function ability(string $ability): string
    {
        return str(static::uriKey())->camel()->append('::')->append($ability)->toString();
    }

    public static function can(Request $request, string $ability): bool
    {
        return Nova::user($request)->can(
            static::ability($ability)
        );
    }

    /**
     * Determine if the given resource is authorizable.
     */
    public static function authorizable(): bool
    {
        $user = Nova::user(request());

        return $user instanceof Authenticatable && $user->roles()->exists();
    }

    /**
     * Determine if the resource should be available for the given request.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeToViewAny(Request $request)
    {
        if (! static::authorizable()) {
            return;
        }

        $gate = Gate::getPolicyFor(static::newModel());

        if (! is_null($gate) && method_exists($gate, 'viewAny')) {
            $this->authorizeTo($request, 'viewAny');
        }
    }

    /**
     * Determine if the resource should be available for the given request.
     *
     * @return bool
     */
    public static function authorizedToViewAny(Request $request)
    {
        if (! static::authorizable()) {
            return true;
        }

        return static::can($request, 'viewAny');
    }

    /**
     * Determine if the current user can view the given resource or throw an exception.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeToView(Request $request)
    {
        $this->authorizeTo($request, 'view');
    }

    /**
     * Determine if the current user can view the given resource.
     *
     * @return bool
     */
    public function authorizedToView(Request $request)
    {
        return $this->authorizedTo($request, 'view');
    }

    /**
     * Determine if the current user can create new resources or throw an exception.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public static function authorizeToCreate(Request $request)
    {
        throw_unless(static::authorizedToCreate($request), AuthorizationException::class);
    }

    /**
     * Determine if the current user can create new resources.
     *
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        if (static::authorizable()) {
            return static::can($request, 'create');
        }

        return true;
    }

    /**
     * Determine if the current user can update the given resource or throw an exception.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeToUpdate(Request $request)
    {
        $this->authorizeTo($request, 'update');
    }

    /**
     * Determine if the current user can update the given resource.
     *
     * @return bool
     */
    public function authorizedToUpdate(Request $request)
    {
        return $this->authorizedTo($request, 'update');
    }

    /**
     * Determine if the current user can replicate the given resource or throw an exception.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeToReplicate(Request $request)
    {
        if (! static::authorizable()) {
            return;
        }

        $gate = Gate::getPolicyFor(static::newModel());

        if (! is_null($gate) && method_exists($gate, 'replicate')) {
            $this->authorizeTo($request, 'replicate');

            return;
        }

        $this->authorizeToCreate($request);
        $this->authorizeToUpdate($request);
    }

    /**
     * Determine if the current user can replicate the given resource.
     *
     * @return bool
     */
    public function authorizedToReplicate(Request $request)
    {
        if (! static::authorizable()) {
            return true;
        }

        return static::can($request, 'replicate');
    }

    /**
     * Determine if the current user can delete the given resource or throw an exception.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeToDelete(Request $request)
    {
        $this->authorizeTo($request, 'delete');
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return $this->authorizedTo($request, 'delete');
    }

    /**
     * Determine if the current user can restore the given resource.
     *
     * @return bool
     */
    public function authorizedToRestore(Request $request)
    {
        return $this->authorizedTo($request, 'restore');
    }

    /**
     * Determine if the current user can force delete the given resource.
     *
     * @return bool
     */
    public function authorizedToForceDelete(Request $request)
    {
        return $this->authorizedTo($request, 'forceDelete');
    }

    /**
     * Determine if the user can add / associate models of the given type to the resource.
     *
     * @param  \Illuminate\Database\Eloquent\Model|string  $model
     * @return bool
     */
    public function authorizedToAdd(NovaRequest $request, $model)
    {
        if (! static::authorizable()) {
            return true;
        }

        $method = 'add'.class_basename($model);

        return static::can($request, $method);
    }

    /**
     * Determine if the user can attach any models of the given type to the resource.
     *
     * @param  \Illuminate\Database\Eloquent\Model|string  $model
     * @return bool
     */
    public function authorizedToAttachAny(NovaRequest $request, $model)
    {
        if (! static::authorizable()) {
            return true;
        }

        $method = 'attachAny'.Str::singular(class_basename($model));

        return static::can($request, $method);
    }

    /**
     * Determine if the user can attach models of the given type to the resource.
     *
     * @param  \Illuminate\Database\Eloquent\Model|string  $model
     * @return bool
     */
    public function authorizedToAttach(NovaRequest $request, $model)
    {
        if (! static::authorizable()) {
            return true;
        }

        $method = 'attach'.Str::singular(class_basename($model));

        return static::can($request, $method);
    }

    /**
     * Determine if the user can detach models of the given type to the resource.
     *
     * @param  \Illuminate\Database\Eloquent\Model|string  $model
     * @param  string  $relationship
     * @return bool
     */
    public function authorizedToDetach(NovaRequest $request, $model, $relationship)
    {
        if (! static::authorizable()) {
            return true;
        }

        $method = 'detach'.Str::singular(class_basename($model));

        return static::can($request, $method);
    }

    /**
     * Determine if the user can run the given action.
     *
     * @return bool
     */
    public function authorizedToRunAction(NovaRequest $request, Action $action)
    {
        if ($action instanceof DestructiveAction) {
            return $this->authorizedToRunDestructiveAction($request, $action);
        }

        if (! static::authorizable()) {
            return true;
        }

        return static::can($request, 'runAction');
    }

    /**
     * Determine if the user can run the given action.
     *
     * @return bool
     */
    public function authorizedToRunDestructiveAction(NovaRequest $request, DestructiveAction $action)
    {
        if (! static::authorizable()) {
            return true;
        }

        return static::can($request, 'runDestructiveAction');
    }

    /**
     * Determine if the current user can impersonate the given resource.
     *
     * @return bool
     */
    public function authorizedToImpersonate(NovaRequest $request)
    {
        $user = Nova::user($request);

        return app(ImpersonatesUsers::class)->impersonating($request) === false
            && ! $this->resource->is($user)
            && $this->resource instanceof Authenticatable
            && (
                self::can($request, 'canBeImpersonated') && (method_exists($this->resource, 'canBeImpersonated') && $this->resource->canBeImpersonated() === true)
            )
            && (
                self::can($request, 'canImpersonate') && (method_exists($user, 'canImpersonate') && $user->canImpersonate() === true)
            );
    }

    /**
     * Determine if the current user has a given ability.
     *
     * @param  string  $ability
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeTo(Request $request, $ability)
    {
        if (static::authorizable()) {
            $authorize = new Response(
                static::can($request, $ability),
                'You are not authorized to perform this action.',
                403
            );

            $authorize->authorize();
        }
    }

    /**
     * Determine if the current user can view the given resource.
     *
     * @param  string  $ability
     * @return bool
     */
    public function authorizedTo(Request $request, $ability)
    {
        return static::authorizable() ? static::can($request, $ability) : true;
    }
}
