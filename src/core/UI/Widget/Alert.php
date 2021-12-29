<?php

namespace Duxravel\Core\UI\Widget;

/**
 * 警告信息
 * @package Duxravel\Core\UI\Widget
 */
class Alert extends Widget
{

    protected string $type = 'info';
    private ?string $title;
    private $content;

    /**
     * @param string|array $content
     * @param string|null $title
     * @param callable|null $callback
     */
    public function __construct($content, string $title = null, callable $callback = NULL)
    {
        $this->title = $title;
        $this->content = $content;
        $this->callback = $callback;
    }

    /**
     * 文本类型
     * @param $name
     * @return $this
     */
    public function type($name): self
    {
        $this->type = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        return [
            'nodeName' => 'a-alert',
            'title' => $this->title,
            'type' => $this->type,
            'child' => $this->content
        ];
    }
}
