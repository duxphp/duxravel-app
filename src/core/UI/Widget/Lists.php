<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;

/**
 * Class Lists
 * @package Duxravel\Core\UI\Widget
 */
class Lists extends Widget
{

    private array $data;
    private bool $row = true;

    /**
     * Lists constructor.
     * @param $data
     * @param int $col
     * @param callable|null $callback
     */
    public function __construct($data, callable $callback = NULL)
    {
        $this->data = $data;
        $this->callback = $callback;
    }

    /**
     * @param $bool
     * @return $this
     */
    public function row($bool)
    {
        $this->row = $bool;
        return $this;
    }


    /**
     * @return array
     */
    public function render(): array
    {

        $inner = [];
        $count = count($this->data);
        $i = 0;
        foreach ($this->data as $item) {
            $inner[] = [
                'nodeName' => 'a-list-item',
                'child' => $item
            ];
        }

        return $inner ? [
            'nodeName' => 'a-list',
            'child' => $inner
        ] : [
            'nodeName' => 'a-empty',
        ];
    }

}
