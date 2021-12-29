<?php

namespace Duxravel\Core\UI\Widget;

/**
 * Class Images
 * @package Duxravel\Core\UI\Widget
 */
class Images extends Widget
{

    private array $list;
    private int $size = 60;

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
        $list = [];
        foreach ($this->list as $vo) {
            $list[] = [
                'nodeName' => 'a-image',
                'src' => $vo,
                'width' => $this->size,
                'height' => $this->size,
            ];
        }

        return [
            'nodeName' => 'a-image-preview-group',
            'infinite' => true,
            'child' => [
                'nodeName' => 'a-space',
                'child' => $list
            ]
        ];

    }

}
