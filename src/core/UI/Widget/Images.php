<?php

namespace Duxravel\Core\UI\Widget;

/**
 * Class Images
 * @package Duxravel\Core\UI\Widget
 */
class Images extends Widget
{

    private array $list;
    private int $size = 10;

    /**
     * Images constructor.
     * @param array $list
     * @param callable|null $callback
     */
    public function __construct(array $list, callable $callback = NULL)
    {
        $this->list = $list;
        $this->callback = $callback;
    }

    /**
     * 图像大小
     * @param $size
     * @return $this
     */
    public function size(int $size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $this->class('flex items-center space-x-4');
        $inner = [];
        foreach ($this->list as $vo) {
            $inner[] = '<div data-js="show-tooltip" data-title="' . $vo['title'] . '"><div  class="avatar w-' . $this->size . ' h-' . $this->size . '" style="background-image: url(' . $vo['image'] . ');"></div></div>';
        }
        $innerHtml = implode('', $inner);
        return <<<HTML
            <div>
                $innerHtml
            </div>
        HTML;

    }

}
