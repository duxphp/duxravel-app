<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;
use Duxravel\Core\UI\Widget\Append\Element;

/**
 * Class Text
 * @package Duxravel\Core\UI\Widget
 */
class Text extends Widget
{
    use Element;

    private $content;

    public function __construct($content, callable $callback = NULL)
    {
        $this->content = $content;
        $this->callback = $callback;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return <<<HTML
            <span>$this->content</span>
        HTML;

    }

}
