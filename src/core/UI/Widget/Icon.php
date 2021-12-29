<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Widget\Append\Element;

/**
 *图标组件
 * @package Duxravel\Core\UI\Widget
 */
class Icon extends Widget
{
    use Element;

    private string $content;
    private bool $layout = false;
    private int $size;

    /**
     * @param string        $content
     * @param callable|null $callback
     */
    public function __construct(string $content, ?callable $callback = null)
    {
        $this->content = $content;
        $this->callback = $callback;
    }

    /**
     * 设置大小
     * @param int $size
     * @return $this
     */
    public function size(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $icon = $this->content;
        if (strpos($icon, '<svg') !== false) {
            return [
                'nodeName' => 'n-icon',
                'size' => $this->size,
                'child' => [
                    'nodeName' => 'rich-text',
                    'class' => implode(' ', $this->class),
                    'nodes' => $icon
                ]
            ];
        }

        return [
            'nodeName' => 'icon-' . $icon,
            'class' => implode(' ', $this->class),
        ];
    }

}
