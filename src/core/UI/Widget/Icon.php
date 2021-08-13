<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;
use Duxravel\Core\UI\Widget\Append\Element;

/**
 * Class Icon
 * @package Duxravel\Core\UI\Widget
 */
class Icon extends Widget
{
    use Element;

    private string $content;
    private bool $layout = false;

    /**
     * Icon constructor.
     * @param $content
     * @param callable|null $callback
     */
    public function __construct($content, callable $callback = NULL)
    {
        $this->content = $content;
        $this->callback = $callback;
    }

    public function size($size = null)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $icon = $this->content;
        if (strpos($icon, '<svg') !== false) {
            return [
                'nodeName' => 'n-icon',
                'size' => $this->size,
                'child' => [
                    'nodeName' => 'rich-text',
                    'class' => $this->toClass(),
                    'nodes' => $icon
                ]
            ];
        } else {
            return [
                'nodeName' => 'n-icon',
                'size' => $this->size,
                'child' => [
                    'nodeName' => $icon.'-icon',
                    'class' => $this->toClass(),
                ]
            ];
        }
    }

}
