<?php

namespace Duxravel\Core\Events;

/**
 * 管理表单事件
 */
class ManageForm
{
    public $form;
    public $class;

    public function __construct($class, $form)
    {
        $this->form = $form;
        $this->class = $class;
    }

}

