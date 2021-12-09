<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form;

/**
 * Class Tab
 * @package Duxravel\Core\UI\Table
 */
class Card extends Composite implements Component
{
    protected $callback;

    public function __construct($callback)
    {
        $this->callback = $callback;
        $form = new Form();
        $form->dialog($this->dialog);
        $form->vertical($this->vertical);
        $callback($form);
        $this->column[] = [
            'object' => $form,
        ];
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($info)
    {
        $inner = [];
        foreach ($this->column as $vo) {
            $inner = $vo['object']->renderForm($info);
        }

        if (!$this->dialog) {
            $this->class('mb-4 bg-white dark:bg-blackgray-4 rounded shadow p-7 pb-2');
        }

        return [
            'nodeName' => 'div',
            'class' => implode(' ', $this->class),
            'child' => $inner
        ];
    }

}
