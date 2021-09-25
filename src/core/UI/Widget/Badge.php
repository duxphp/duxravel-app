<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;

/**
 * Class Badges
 * @package Duxravel\Core\UI\Widget
 */
class Badge extends Widget
{

    private string $content;
    private string $url = '';
    private string $size = 'base';
    private string $type;

    /**
     * Badges constructor.
     * @param $content
     * @param callable|null $callback
     */
    public function __construct($content, callable $callback = NULL)
    {
        $this->content = $content;
        $this->callback = $callback;
    }

    /**
     * 颜色
     * @param $value
     * @return $this
     */
    public function color($value): self
    {
        $this->type = $value;
        return $this;
    }

    /**
     * 链接
     * @param string $value
     * @return $this
     */
    public function url(string $value): self
    {
        $this->url = $value;
        return $this;
    }

    /**
     * 大小
     * @param string $size
     * @return $this
     */
    public function size(string $size = '')
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return array
     */
    public function render()
    {
        return [
            'nodeName' => 'n-tag',
            'type' => $this->type,
            'size' => $this->size,
            'child' => $this->content
        ];
    }

}
