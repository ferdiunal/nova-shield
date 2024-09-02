<?php

namespace Ferdiunal\NovaShield\Http\Nova;

use App\Nova\Resource;
use Ferdiunal\NovaShield\NovaShieldPanel;
use Ferdiunal\NovaShield\ShieldField;
use Laravel\Nova\Fields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class ShieldResource extends Resource
{
    /** @var class-string<\Spatie\Permission\Models\Role> */
    public static $model = \Spatie\Permission\Models\Role::class;

    public static $title = "name";

    public static $search = [
        "name",
        "guard_name",
    ];

    public static $displayInNavigation = false;

    public static $with = ['permissions'];

    public function fields(NovaRequest $request)
    {
        $mode = "form";
        if ($request->isResourceDetailRequest()) {
            $mode = "detail";
        } elseif ($request->isResourceIndexRequest()) {
            $mode = "index";
        }

        return [
            Fields\ID::make()->sortable(),
            Fields\Text::make('Name')->sortable(),
            Fields\Text::make('Guard Name')->sortable(),
            NovaShieldPanel::make('Permissions', [
                ShieldField::make('Permissions', 'permissions')
            ])->mode($mode),
            Fields\DateTime::make('Created At')->sortable()->exceptOnForms(),
            Fields\DateTime::make('Updated At')->sortable()->exceptOnForms(),
        ];
    }
}
