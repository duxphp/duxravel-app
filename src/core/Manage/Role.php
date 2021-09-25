<?php

namespace Duxravel\Core\Manage;

use Duxravel\Core\UI\Form;
use Duxravel\Core\UI\Table;

trait Role
{

    private function parserData()
    {
        $parsing = app_parsing();
        $route = strtolower($parsing['layer']) . '.' . strtolower($parsing['app']) . '.role';
        $app = $parsing['app'];
        $model = '\\Modules\\' . $app . '\\Model\\' . $app . 'Role';
        return [
            'route' => $route,
            'model' => $model
        ];
    }

    protected function table(): Table
    {
        $parser = $this->parserData();
        $table = new Table(new $parser['model']());
        $table->title('角色管理');

        $table->filter('角色名称', 'name', function ($query, $value) {
            $query->where('name', 'like', '%' . $value . '%');
        })->text('请输入角色名称')->quick();

        $table->action()->button('添加', $parser['route'] . '.page');

        $table->column('角色名称', 'name');

        $column = $table->column('操作')->width(200);
        $column->link('编辑', $parser['route'] . '.page', ['id' => 'role_id']);
        $column->link('删除', $parser['route'] . '.del')->type('ajax', ['method' => 'post']);
        return $table;
    }

    public function form(int $id = 0): Form
    {
        $parser = $this->parserData();
        $form = new Form(new $parser['model']());
        $form->setKey('role_id', $id);
        $form->title('角色信息');
        $info = $form->info();
        $form->card(function ($form) use ($info) {
            $this->formInner($form, $info);
        });


        $form->before(function ($data, $type, $model) {
            $purview = $data['purview'];
            $purview = array_filter($purview, function ($item) {
                if (stripos($item, 'desc_', 0) !== false) {
                    return false;
                }
                return true;
            });
            $model->purview = array_values($purview);
        });

        return $form;
    }

    public function formInner($form)
    {
        $form->text('角色名', 'name')->verify([
            'required',
            'min:2',
        ], [
            'required' => '请填写角色名',
            'min' => '用户名不能少于2位',
        ]);


        $form->tree('权限选择', 'purview', function () {

            $parsing = app_parsing();
            $data = $this->getAuthAll(strtolower($parsing['layer']));

            $purviewData = [];
            $i = 0;
            foreach ($data as $appName => $app) {
                $i++;
                $tmp = [
                    'id' => 'desc_' . $i,
                    'name' => $app['name'],
                    'children' => []
                ];

                foreach ($app['group'] as $groupName => $item) {
                    $i++;
                    $group = [
                        'id' => 'desc_' . $i,
                        'name' => $item['name'],
                        'children' => []
                    ];
                    foreach ($item['list'] as $vo) {
                        if ($vo['auth_list']) {
                            foreach ($vo['auth_list'] as $k => $v) {
                                $group['children'][] = [
                                    'id' => $vo['value'] . '|' . $k,
                                    'name' => $v
                                ];
                            }
                        } else {
                            $group['children'][] = [
                                'id' => $vo['value'],
                                'name' => $vo['name']
                            ];
                        }
                    }
                    $tmp['children'][] = $group;
                }
                $purviewData[] = $tmp;
            }

            return $purviewData;
        })->help('全部不选择为拥有所有权限', true);

        return $form;
    }

    public function getAuthAll($has = 'admin'): array
    {
        $app = app();
        $routes = $app->routes->getRoutes();
        $data = [];
        foreach ($routes as $vo) {
            if ($vo->action['auth_has'] <> $has || $vo->action['public']) {
                continue;
            }
            if (!$data[$vo->action['auth_app']]) {
                $data[$vo->action['auth_app']] = [
                    'name' => $vo->action['auth_app'],
                    'group' => []
                ];
            }
            if (!$data[$vo->action['auth_app']]['group'][$vo->action['auth_group']]) {
                $data[$vo->action['auth_app']]['group'][$vo->action['auth_group']] = [
                    'name' => $vo->action['auth_group'],
                    'list' => []
                ];
            }
            $data[$vo->action['auth_app']]['group'][$vo->action['auth_group']]['list'][] = [
                'name' => $vo->action['desc'],
                'value' => $vo->action['as'],
                'auth_list' => $vo->action['auth_list']
            ];
        }
        return $data;
    }

}
