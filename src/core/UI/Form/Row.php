<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Composite;
use Duxravel\Core\UI\Form;
use Duxravel\Core\UI\Tools;

/**
 * Class Row
 * @package Duxravel\Core\UI\Table
 */
class Row extends Composite implements Component
{
    public function __construct()
    {
        $this->layout = false;
    }

    /**
     * 设置列
     * @param callable $callback
     * @param int $width
     * @return $this
     */
    public function column(callable $callback, int $width = 0): self
    {
        $form = new Form();
        $callback($form);
        $this->column[] = [
            'width' => $width,
            'object' => $form,
        ];
        return $this;
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value)
    {
        $inner = [];
        foreach ($this->column as $vo) {
            $width = $vo['width'] ? "lg:row-span-{$vo['width']}" : '';
            $form = $vo['object']->renderForm($value);
            $inner[] = [
                'nodeName' => 'div',
                'class' => $width,
                'child' => $form
            ];
        }

        return [
            'nodeName' => 'div',
            'class' => 'grid lg:grid-flow-col gap-4',
            'child' => $inner
        ];
    }

}
