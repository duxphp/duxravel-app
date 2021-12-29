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

    /**
     * Lists constructor.
     * @param               $data
     * @param callable|null $callback
     */
    public function __construct($data, callable $callback = NULL)
    {
        $this->data = $data;
        $this->callback = $callback;
    }


    /**
     * @return array
     */
    public function render(): array
    {

        $inner = [];
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
