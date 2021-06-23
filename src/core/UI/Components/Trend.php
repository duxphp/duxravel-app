<?php

namespace Duxravel\Core\UI\Components;

use Illuminate\View\Component;

/**
 * 趋势图标
 * Class Trend
 * @package Duxravel\Core\UI\Components
 */
class Trend extends Component
{
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function render()
    {
        return view('Common.UI.View.Components.trend');
    }
}
