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

    /**
     * 嵌套元素
     * @param bool $status
     * @return $this
     */
    public function layout(bool $status = true): self
    {
        $this->layout = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {

        if (is_file(resource_path('image/icons/' . $this->content . '.svg'))) {
            $icon = file_get_contents(resource_path('image/icons/' . $this->content . '.svg'));
        } elseif (strpos($this->content, '<svg') !== false) {
            $icon = $this->content;
        } else {
            $this->class($this->content);
            $icon = <<<HTML
                <i {$this->toElement()}></i>
            HTML;
        }

        if ($this->layout) {
            if (strpos($icon, '<svg') !== false) {
                $icon = preg_replace('/ class="([^\"]*)"/isU', '', $icon);
                $icon = str_replace('<svg', '<svg class="stroke-current w-full h-full"', $icon);
            }
            return <<<HTML
                <span {$this->toElement()}>$icon</span>
            HTML;
        }
        $this->class('stroke-current w-full h-full');
        if (strpos($icon, '<svg') !== false) {
            $icon = preg_replace('/ class="([^\"]*)"/isU', '', $icon);
            return str_replace('<svg', '<svg ' . $this->toElement(), $icon);
        }
        return $icon;
    }

}
