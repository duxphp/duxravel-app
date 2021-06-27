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
        $table->model()->orderBy('operate_id', 'desc');

        $table->filter('用户', 'user_id', function ($query, $value) {
            $query->where('has_id', $value);
        })->select([], function ($object) use ($route) {
            $object->search(route($route . '.user.data'));
        })->quick();

        $table->filter('开始日期', 'start', function ($query, $value) {
            $query->where('create_time', '>=', strtotime($value));
        })->date();
        $table->filter('结束日期', 'stop', function ($query, $value) {
            $query->where('update_time', '<=', strtotime($value));
        })->date();

        $table->column('用户', 'username');
        $table->column('页面', 'desc', function ($value, $item) {
            return Widget::Badge($item['method'], function (Widget\Badge $badge) use ($item) {
                    $badge->size('small');
                    if ($item['method'] === 'GET') {
                        $badge->color('blue');
                    } else {
                        $badge->color('red');
                    }
                }) . ' <span class="ml-2">' . $item['desc'] . '</span>';
        })->desc('name');
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
        $table->column('操作时间', 'update_time')->desc('time', function ($value) {
            return $value . 's';
        });


        $column = $table->column('详情');
        $column->link('查看数据', $route . '.operate.info', ['id' => 'operate_id'])->type('dialog')->data(['size' => 'large']);

        return $table;
    }

    public function loadData()
    {
        $parser = app_parsing();
        $layer = strtolower($parser['layer']);
        $apiList = \Duxravel\Core\Model\VisitorOperate::orderBy('create_time', 'desc')
            ->where('has_type', $layer)
            ->where('has_id', auth($layer)->user()->user_id)
            ->limit(10)
            ->get(['method', 'name', 'route', 'desc', 'time', 'create_time']);

        $this->assign('apiList', $apiList);
        return $this->dialogView('vendor.duxphp.duxravel-app.src.core.Views.Manage.VisitorOperate.loadData');
    }

    public function info($id)
    {
        $info = \Duxravel\Core\Model\VisitorOperate::find($id);

        $params = $info['params'];

        $data = [];
        foreach ($params as $key => $vo) {
            $data[] = [
                'name' => $key,
                'value' => $vo
            ];
        }
        $table = new \Duxravel\Core\UI\Table(collect($data));
        $table->limit(50);
        $table->column('键', 'name');
        $table->column('值', 'value');
        $table->dialog(true);
        return $table->render();
    }

}
