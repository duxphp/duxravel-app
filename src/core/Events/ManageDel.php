<?php

namespace Duxravel\Core\Events;

/**
 * 管理删除事件
 */
class ManageDel
{
    public $id;
    public $class;

    public function __construct($class, $id)
    {
        $this->id = $id;
        $this->class = $class;
    }

}

