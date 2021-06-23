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
        $this->class[] = 'border-' . $value . '-900';
        $this->class[] = 'text-' . $value . '-900';
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
    public function size(string $size = 'base')
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $this->class('cursor-pointer select-none rounded border');
        if ($this->size === 'large') {
            $this->class('text-sm px-4 py-2');
        }
        if ($this->size === 'small') {
            $this->class('text-xs py-0 px-1');
        }
        if ($this->size === 'base') {
            $this->class('text-sm px-2 py-1');
        }

        if ($this->url) {
            $label = 'a';
            $this->attr('href', $this->url ?: '#');
        } else {
            $label = 'span';
        }

        return <<<HTML
            <$label {$this->toElement()}>
            $this->content
            </$label>
        HTML;
    }

}
