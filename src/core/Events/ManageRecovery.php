<?php

namespace Duxravel\Core\Events;

/**
 * 管理恢复事件
 */
class ManageRecovery
{
    public $id;
    public $class;

    public function __construct($class, $id)
    {
        $this->id = $id;
        $this->class = $class;
    }

}

