<?php

namespace Duxravel\Core\Events;

/**
 * 管理清空事件
 */
class ManageClear
{
    public $id;
    public $class;

    public function __construct($class, $id)
    {
        $this->id = $id;
        $this->class = $class;
    }

}

