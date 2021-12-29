<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Row
 * @package Duxravel\Core\UI\Table
 */
class Layout extends Composite implements Component
{
    protected $callback;

    /**
     * @param callable|array $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $callback = is_callable($this->callback) ? call_user_func($this->callback) : $this->callback;
        return [
            'nodeName' => 'div',
            'class' => 'mb-4',
            'child' => $callback
        ];

    }

}
