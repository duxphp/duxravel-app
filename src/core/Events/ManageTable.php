<?php

namespace Duxravel\Core\Events;

/**
 * 管理表格事件
 */
class ManageTable
{
    public $table;
    public $class;

    public function __construct($class, $table)
    {
        $this->table = $table;
        $this->class = $class;
    }

}

