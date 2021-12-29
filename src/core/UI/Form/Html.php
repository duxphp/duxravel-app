<?php

namespace Duxravel\Core\UI\Form;

/**
 * 自定义Html
 * @package Duxravel\Core\UI\Table
 */
class Html extends Element implements Component
{
    protected \Closure $callback;

    /**
     * @param string   $name
     * @param \Closure $callback
     */
    public function __construct(string $name, \Closure $callback)
    {
        $this->name = $name;
        $this->callback = $callback;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $callback = is_callable($this->callback) ? call_user_func($this->callback) : $this->callback;

        if (is_array($callback)) {
            return $callback;
        }
        return [
            'nodeName' => 'rich-text',
            'nodes' => $callback
        ];

    }

}
