<?php

namespace Ferdiunal\NovaShield\Lib;

use Laravel\Nova\Fields\Field;

abstract class NovaTeamHelperField
{
    /**
     * The field's component.
     */
    abstract public static function field(): Field;
}
