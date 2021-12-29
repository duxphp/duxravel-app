<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;

/**
 * 项目回调
 * @package Duxravel\Core\UI\Widget
 */
class Item
{
    public $params;

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    public function __call($method, $arguments)
    {
        $this->{$method}[] = $arguments;
        return $this;
    }

}
