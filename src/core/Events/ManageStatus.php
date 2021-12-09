<?php

namespace Duxravel\Core\Events;

/**
 * 管理状态事件
 */
class ManageStatus
{
    public $id;
    public $class;

    public function __construct($class, $id)
    {
        $this->id = $id;
        $this->class = $class;
    }

}

