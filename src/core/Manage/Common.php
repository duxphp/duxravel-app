<?php

namespace Duxravel\Core\Manage;

use Duxravel\Core\UI\Widget\Icon;
use Duxravel\Core\Util\View;

/**
 * 管理端基础接口
 * @package Duxravel\Core\Model
 */
trait Common
{

    protected array $assign = [];

    /**
     * 模板赋值
     * @param $name
     * @param $value
     */
    public function assign($name, $value): void
    {
        $this->assign[$name] = $value;
    }

    public function systemView(string $tpl = '', string $route = '')
    {
        return (new View($tpl, $this->assign))->route($route)->render();
    }

    public function layoutView(string $tpl = '')
    {
        return (new View($tpl, $this->assign))->render('layout');
    }

    public function dialogView(string $tpl = '')
    {
        return (new View($tpl, $this->assign))->render('dialog');
    }

    public function dialogNode(string $title = '', array $node = [])
    {
        return app_success('ok', [
            'node' => [
                [
                    'nodeName' => 'div',
                    'class' => 'flex items-center p-4 border-b border-gray-200',
                    'child' => [
                        [
                            'nodeName' => 'div',
                            'class' => 'flex-grow text-xl',
                            'child' => $title ?: '信息详情'
                        ],
                        [
                            'nodeName' => 'route',
                            'type' => 'back',
                            'class' => 'flex items-center',
                            'child' => (new Icon('x'))->size(20)->class('cursor-pointer text-gray-600 hover:text-red-600')->getRender()
                        ]
                    ]
                ],
                $node
            ]
        ]);
    }

    public function can($name)
    {
        $route = request()->route()->getName();
        $purview = app()->make('purview');
        if ($purview && !in_array($route . '|' . $name, $purview)) {
            app_error('没有权限使用该功能', 403);
        }
    }

}
