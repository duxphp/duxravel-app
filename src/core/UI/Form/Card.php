<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form;

/**
 * 卡片布局
 * @package Duxravel\Core\UI\Table
 */
class Card extends Composite implements Component
{
    protected \Closure $callback;

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
     * @return array
     */
    public function render(): array
    {
        $inner = [];
        foreach ($this->column as $vo) {
            $inner = $vo['object']->renderForm();
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
