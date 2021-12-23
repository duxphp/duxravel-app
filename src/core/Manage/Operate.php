<?php

namespace Duxravel\Core\Manage;

use Duxravel\Core\UI\Table;
use Duxravel\Core\UI\Widget;

trait Operate
{


    protected function table(): Table
    {

        $parser = app_parsing();
        $layer = strtolower($parser['layer']);
        $route = strtolower($parser['layer']) . '.' . strtolower($parser['app']);
        $table = new \Duxravel\Core\UI\Table(new \Duxravel\Core\Model\VisitorOperate());
        $table->title('操作日志');
        $table->model()->where('has_type', $layer);
        $table->model()->orderBy('updated_at', 'desc');

        $table->map([
            'method',
            'created_at' => function($item) {
                return $item->created_at->format('Y-m-d H:i:s');
            },
            'updated_at' => function($item) {
                return $item->updated_at->format('Y-m-d H:i:s');
            }
        ]);

        $table->filter('用户', 'user_id', function ($query, $value) {
            $query->where('has_id', $value);
        })->select([], function ($object) use ($route) {
            $object->search(route($route . '.user.data'));
        })->quick();

        $table->filter('开始日期', 'start', function ($query, $value) {
            $query->where('created_at', '>=', $value);
        })->date();
        $table->filter('结束日期', 'stop', function ($query, $value) {
            $query->where('updated_at', '<=', $value);
        })->date();

        $table->column('用户', 'username');
        $table->column('页面', 'desc')->desc('name');
        $table->column('客户端', 'ip')->desc('ua', function ($value, $item) {
            $html = [];
            if ($item->mobile) {
                $html[] = Widget::Icon('fa fa-phone');
            } else {
                if ($item->device === 'OS X') {
                    $html[] = Widget::Icon('fab fa-apple');
                } elseif ($item->device === 'Windows') {
                    $html[] = Widget::Icon('fab fa-windows');
                } else {
                    $html[] = Widget::Icon('fab fa-linux');
                }
            }
            return implode(' ', $html) . ' ' . $item->device . ' - ' . $item->browser;
        });
        $table->column('操作时间', 'updated_at')->desc('time', function ($value) {
            return $value . 's';
        });

        $column = $table->column('详情')->width(150);
        $column->link('查看数据', $route . '.operate.info', ['id' => 'uuid'])->type('drawer');

        return $table;
    }

    public function info($id)
    {
        $info = \Duxravel\Core\Model\VisitorOperate::find($id);


        $data = [];
        $data[] = [
            'label' => '用户',
            'value' => $info->username,
        ];
        $data[] = [
            'label' => '方式',
            'value' => $info->method,
        ];
        $data[] = [
            'label' => '路由',
            'value' => $info->route,
        ];
        $data[] = [
            'label' => '描述',
            'value' => $info->desc,
        ];
        $data[] = [
            'label' => 'IP',
            'value' => $info->ip,
        ];
        $data[] = [
            'label' => '浏览器',
            'value' => $info->browser,
        ];
        $data[] = [
            'label' => '系统',
            'value' => $info->device,
        ];
        $data[] = [
            'label' => '响应',
            'value' => $info->time . 's',
        ];
        $data[] = [
            'label' => '请求时间',
            'value' => $info->created_at->format('Y-m-d H:i:s'),
        ];
        $data[] = [
            'label' => '结束时间',
            'value' => $info->updated_at->format('Y-m-d H:i:s'),
        ];

        return $this->dialogNode('操作详情', [
            'nodeName' => 'div',
            'class' => 'p-4',
            'child' => [
                [
                    'nodeName' => 'div',
                    'child' => '用户信息',
                    'class' => 'pb-4 text-base',
                ],
                [
                    'nodeName' => 'a-descriptions',
                    'column' => 1,
                    'bordered' => true,
                    'data' => $data
                ],
                $info->params ? [
                    'nodeName' => 'div',
                    'child' => '请求数据',
                    'class' => ' py-4 text-base',
                ] : [],
                $info->params ? [
                    'nodeName' => 'pre',
                    'class' => 'bg-gray-100 block p-4',
                    'child' => json_encode($info->params, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)
                ] : [],
            ]
        ]);
    }

}
