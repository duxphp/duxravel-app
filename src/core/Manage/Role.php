<?php

namespace Duxravel\Core\Manage;

use Duxravel\Core\Facades\Permission;
use Duxravel\Core\UI\Form;
use Duxravel\Core\UI\Table;
use Duxravel\Core\Model\Role as AuthRole;

trait Role
{
    private $config = [];

    private function parserData()
    {
        $parsing = app_parsing();

        $this->config['layer'] = $parsing['layer'];
        $this->config['guard_has'] = request()->get('global_guard_has', null);

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
        $table = new Table(new AuthRole());
        $table->model()->where('guard', $this->config['layer'])->where('guard_has', $this->config['guard_has']);
        $table->title('角色管理');

        $table->filter('角色名称', 'name', function ($query, $value) {
            $query->where('name', 'like', '%' . $value . '%');
        })->text('请输入角色名称')->quick();

        $table->action()->button('添加', $parser['route'] . '.page')->type('dialog');

        $table->column('角色名称', 'name');

        $column = $table->column('操作')->width(200);
        $column->link('编辑', $parser['route'] . '.page', ['id' => 'role_id'])->type('dialog');
        $column->link('删除', $parser['route'] . '.del')->type('ajax', ['method' => 'post']);
        return $table;
    }

    public function form(int $id = 0): Form
    {
        $this->parserData();
        $form = new Form(new AuthRole());
        $form->model()->where('guard', $this->config['layer'])->where('guard_has', $this->config['guard_has']);
        $form->title('角色信息');
        $form->card(function ($form) {
            $this->formInner($form);
        });

        $form->before(function ($data, $type, $model) {
            $model->guard = strtolower($this->config['layer']);
            $model->guard_has = $this->config['guard_has'];
            $purview = explode(',', $data['purview']);
            $purview = array_filter($purview, function ($item) {
                if (stripos($item, 'desc_', 0) !== false) {
                    return false;
                }
                return true;
            });
            $model->purview = array_filter(array_values($purview));
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
            $data =  Permission::getPermissions();

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


}
