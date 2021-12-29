<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Tools;

/**
 * 复合组件
 * @package Duxravel\Core\UI
 */
class Composite extends Element
{

    public array $column = [];
    public bool $component = true;
    public object $form;

    /**
     * @param null   $key
     * @param string $item
     * @return array|object
     */
    public function getColumn($key = null, string $item = '')
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
                foreach ($item->getInput($time) as $k => $v) {
                    $data[$k] = $v;
                }
            });
        }
        return $data;
    }

    /**
     * @param $info
     * @return array
     */
    public function getData($info): array
    {
        $data = [];
        foreach ($this->column as $vo) {
            $vo['object']->getElement()->map(function ($item) use (&$data, $info) {
                foreach ($item->getData($info) as $k => $v) {
                    $data[$k] = $v;
                }
            });
        }
        return $data;
    }
}
