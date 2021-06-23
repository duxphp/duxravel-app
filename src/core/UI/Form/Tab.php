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
    protected array $column;

    public function __construct()
    {
        $this->layout = false;
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
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        $this->class('tabs');

        $ul = [];
        $body = [];
        foreach ($this->column as $key => $vo) {
            // ul
            $ul[] = <<<HTML
                <li>
                    <a class="tabs-item"  :class="{'tabs-active': tab == $key}" href="javascript:;" @click="tab = $key">{$vo['name']}</a>
               </li>
            HTML;
            // body
            if ($vo['title']) {
                if ($vo['desc']) {
                    $desc = "<p class='text-gray-500'>{$vo['desc']}</p>";
                }else {
                    $desc = '';
                }
                $title = "<div class='mb-6'><div class='text-xl'>{$vo['title']}</div>$desc</div>";
            }else {
                $title = '';
            }
            $inner = $vo['object']->renderForm($value);
            $body[] = <<<HTML
                <div x-show="tab == $key" x-cloak>
                    $title
                    $inner
                </div>
            HTML;

        }
        $ulHtml = implode('', $ul);
        $bodyHtml = implode('', $body);

        return <<<HTML
            <div x-data="{tab: 0}" {$this->toElement()}>
                <ul class="tabs-nav">
                $ulHtml
                </ul>
                <div class="px-6 lg:px-8 py-6">
                $bodyHtml
                </div>
            </div>
        HTML;

    }

}
