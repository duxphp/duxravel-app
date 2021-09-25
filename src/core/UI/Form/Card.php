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

    public function __construct($callback, $dialog = false)
    {
        $this->dialog = $dialog;
        $this->callback = $callback;
        $form = new Form();
        $form->dialog($dialog);
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
            $this->class('mb-3 bg-white rounded shadow px-6 lg:px-8 py-6');
        }

        return [
            'nodeName' => 'div',
            'class' => $this->getClass(),
            'child' => $inner
        ];
    }

}
