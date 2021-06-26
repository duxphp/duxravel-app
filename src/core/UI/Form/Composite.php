<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Tools;

/**
 * 复合组件
 * Class Composite
 * @package Duxravel\Core\UI
 */
class Composite extends Element
{

    public array $column = [];
    public bool $component = true;
    public object $form;

    /**
     * @param null $key
     * @param string $item
     * @return array|object
     */
    public function getColumn($key = null, $item = '')
    {
        return $key !== null ? ($item ? $this->column[$key][$item] : $this->column[$key]) : $this->column;
    }

    /**
     * 获取值
     * @param string $time
     * @return array
     */
    public function getInput(string $time = 'add'): array
    {
        $data = [];
        foreach ($this->column as $vo) {
            $vo['object']->getElement()->map(function ($item) use (&$data, $time) {
                if ($item instanceof \Duxravel\Core\UI\Form\Composite) {
                    foreach ($item->getInput($time) as $k => $v) {
                        $data[$k] = $v;
                    }
                } else {
                    $data[$item->getField()] = $item->getInput($time);
                }
            });
        }
        return $data;
    }
}
