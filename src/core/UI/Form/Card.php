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
        $this->layout = false;
        $this->callback = $callback;
        $form = new Form();
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
    public function render($value): string
    {
        $inner = [];
        foreach ($this->column as $vo) {
            $inner[] = $vo['object']->renderForm($value);
        }
        $innerHtml = implode('', $inner);
        $this->class('px-6 lg:px-8 py-6');
        return <<<HTML
        <div class="mb-3 bg-white shadow" >
            <div {$this->toElement()}>
                $innerHtml
            </div>
        </div>
        HTML;
    }

}
