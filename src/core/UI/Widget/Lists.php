<?php

namespace Duxravel\Core\UI\Widget;

/**
 * Class Lists
 *
 * @see [arco.design] https://arco.design/vue/component/list
 * @package Duxravel\Core\UI\Widget
 */
class Lists extends Widget
{

    private array $data = [];

    private string $size = 'medium'; // 尺寸大小: small,medium,large

    /**
     * Lists constructor.
     *
     * @param  array         $data
     * @param  callable|null $callback
     */
    public function __construct($data = [], callable $callback = null)
    {
        $this->data     = $data;
        $this->callback = $callback;
    }

    /**
     * 尺寸大小
     *
     * @param  string $size
     * @return $this
     */
    public function size(string $size = ''): self
    {
        $this->size = $size;
        return $this;
    }

    /**
     * 添加普通项
     *
     * @param  scalar|array $item
     * @return $this
     */
    public function addItem($item)
    {
        $this->data[] = $item;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {

        $inner = [];
        $i     = 0;
        foreach ($this->data as $item) {
            $inner[] = [
                'nodeName' => 'a-list-item',
                'child'    => $item,
            ];
        }

        return $inner ? [
            'nodeName' => 'a-list',
            'size'     => $this->size,
            'child'    => $inner,
        ] : [
            'nodeName' => 'a-empty',
        ];
    }

}
