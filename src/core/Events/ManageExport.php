<?php

namespace Duxravel\Core\Events;

/**
 * 管理导出事件
 */
class ManageExport
{
    public $table;
    public $class;

    public function __construct($class, $table)
    {
        $this->table = $table;
        $this->class = $class;
    }

}

