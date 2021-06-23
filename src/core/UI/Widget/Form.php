<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;

/**
 * Class Form
 * @package Duxravel\Core\UI\Widget
 */
class Form extends Widget
{

    private \Duxravel\Core\UI\Form $form;

    public function __construct($data, callable $callback = NULL)
    {
        $this->callback = $callback;
        $this->form = new \Duxravel\Core\UI\Form($data, false);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return $this->form->render();
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return $this->form->$method(...$arguments);
    }

}
