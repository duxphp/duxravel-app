<?php

namespace Duxravel\Core\UI\Table\Column;

/**
 * 组件接口
 * Class Component
 * @package Duxravel\Core\UI
 */
interface Component
{
    public function render($label): array;
}
