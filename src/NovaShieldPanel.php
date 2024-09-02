<?php

namespace Ferdiunal\NovaShield;

use Laravel\Nova\Panel;

class NovaShieldPanel extends Panel
{
    public $component = 'NovaShieldPanel';

    public function __construct($name, $fields = [])
    {
        parent::__construct($name, $fields);
    }

    public function mode($mode)
    {
        return $this->withMeta(['mode' => $mode]);
    }
}
