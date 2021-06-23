<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;

/**
 * Class Table
 * @package Duxravel\Core\UI\Widget
 */
class Table extends Widget
{

    private \Duxravel\Core\UI\Table $table;

    public function __construct($data, callable $callback = NULL)
    {
        $this->callback = $callback;
        $this->table = new \Duxravel\Core\UI\Table($data);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return $this->table->render();
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return $this->table->$method(...$arguments);
    }

}
