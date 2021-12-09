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
    public function __construct()
    {
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
        $form->vertical($this->vertical);
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
                'nodeName' => 'a-tab-pane',
                'title' => $vo['name'],
                'key' => $key,
                'class' => !$this->dialog ? ' border-t border-gray-200 dark:border-blackgray-1 px-3 pt-4 pb-0' : '',
                'child' => [
                    'nodeName' => 'div',
                    'class' => '',
                    'child' => $child
                ]
            ];
        }

        return [
            'nodeName' => 'a-tabs',
            'class' => !$this->dialog ? 'mb-4 bg-white dark:bg-blackgray-4 rounded shadow p-4 pb-1' : '',
            'type' => 'rounded',
            'child' => $nodes
        ];

    }

}
