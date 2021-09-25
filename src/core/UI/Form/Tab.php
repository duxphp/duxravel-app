<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form;

/**
 * Class Tab
 * 切换组件
 * @package Duxravel\Core\UI\Table
 */
class Tab extends Composite implements Component
{
    public function __construct($dialog = false)
    {
        $this->dialog = $dialog;
    }

    /**
     * @param $name
     * @param callable $callback
     * @param string $title
     * @param string $desc
     * @return $this
     */
    public function column($name, callable $callback, string $title = '', string $desc = ''): self
    {
        $form = new Form();
        $form->dialog($this->dialog);
        $callback($form);
        $this->column[] = [
            'name' => $name,
            'title' => $title,
            'desc' => $desc,
            'object' => $form,
        ];
        return $this;
    }

    /**
     * 渲染组件
     * @return string
     */
    public function render()
    {

        $nodes = [];

        foreach ($this->column as $key => $vo) {

            $child = [];
            if ($vo['title']) {
                $child[] = [
                    'nodeName' => 'div',
                    'class' => 'py-4 flex flex-col gap-2',
                    'child' => [
                        [
                            'nodeName' => 'div',
                            'class' => 'text-xl',
                            'child' => $vo['title'],
                        ],
                        [
                            'nodeName' => 'div',
                            'class' => 'text-gray-500',
                            'child' => $vo['desc'],
                        ]
                    ]
                ];
            }
            $child[] = [
                'nodeName' => 'div',
                'class' => 'pt-2',
                'child' => $vo['object']->renderForm($value)
            ];

            $nodes[] = [
                'nodeName' => 'n-tab-pane',
                'name' => $vo['name'],
                'tab' => $vo['name'],
                'child' => $child
            ];
        }

        return [
            'nodeName' => 'n-tabs',
            'class' => !$this->dialog ? 'mb-3 bg-white rounded shadow px-6 lg:px-8 py-2' : '',
            'type' => 'line',
            'child' => $nodes
        ];

    }

}
