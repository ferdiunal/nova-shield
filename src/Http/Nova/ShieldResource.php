<?php

namespace Ferdiunal\NovaShield\Http\Nova;

use App\Nova\Resource;
use Ferdiunal\NovaShield\NovaShieldPanel;
use Ferdiunal\NovaShield\ShieldField;
use Laravel\Nova\Fields;
use Laravel\Nova\Http\Requests\NovaRequest;

class ShieldResource extends Resource
{
    /** @var class-string<\Spatie\Permission\Models\Role> */
    public static $model = \Spatie\Permission\Models\Role::class;

    public static $title = 'name';

    public static $search = [
        'name',
        'guard_name',
    ];

    public static $displayInNavigation = false;

    public static $with = ['permissions'];

    public function fields(NovaRequest $request)
    {
        $mode = 'form';
        if ($request->isResourceDetailRequest()) {
            $mode = 'detail';
        } elseif ($request->isResourceIndexRequest()) {
            $mode = 'index';
        }

        return array_filter([
            Fields\ID::make()->sortable(),
            Fields\Text::make('Name')->sortable(),
            $this->teamField(),
            Fields\Text::make('Guard Name')->sortable(),
            NovaShieldPanel::make('Permissions', [
                ShieldField::make('Permissions', 'permissions'),
            ])->mode($mode),
            Fields\DateTime::make('Created At')->sortable()->exceptOnForms(),
            Fields\DateTime::make('Updated At')->sortable()->exceptOnForms(),
        ], fn ($field) => $field !== null);
    }

    public function teamField(): ?Fields\Field
    {
        $teamField = config('nova-shield.teamField');

        if (config('permission.teams', false) && method_exists($teamField, 'field')) {
            $teamField = $teamField::field();
            if (! ($teamField instanceof Fields\Field)) {
                $teamField = null;
            }
        } else {
            $teamField = null;
        }

        return $teamField;
    }
}
