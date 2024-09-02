<?php

namespace Ferdiunal\NovaShield;

use Ferdiunal\NovaShield\Http\Nova\ShieldResource;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaShield extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('nova-shield', __DIR__ . '/../dist/js/tool.js');
        Nova::style('nova-shield', __DIR__ . '/../dist/css/tool.css');
    }

    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function menu(Request $request)
    {
        return MenuSection::make('Nova Shield')
            // ->path('/nova-shield')
            ->path('/resources/' . ShieldResource::uriKey())
            ->canSee(function ($request) {
                return ShieldResource::authorizedToViewAny($request);
            })
            ->icon('shield-check');
    }
}
