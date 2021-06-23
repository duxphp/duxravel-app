<?php

namespace Duxravel\Core\UI\Components;

use Illuminate\View\Component;

/**
 * 加载数据中
 * Class Loading
 * @package Duxravel\Core\UI\Components
 */
class Loading extends Component
{
    public $title;
    public $content;

    public function __construct($title = '加载数据中，请稍等...', $content = '如果长时间未加载您可以尝试重新加载')
    {
        $this->title = $title;
        $this->content = $content;
    }

    public function render()
    {
        return view('vendor.duxphp.duxravel-app.src.core.UI.View.Components.loading');
    }
}
