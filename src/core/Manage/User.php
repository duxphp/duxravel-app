<?php

namespace Duxravel\Core\Manage;

use Illuminate\Validation\Rule;

trait User
{

    private function parserData()
    {
        $parsing = app_parsing();
        $route = strtolower($parsing['layer']) . '.' . strtolower($parsing['app']) . '.user';
        $app = $parsing['app'];
        $model = '\\Modules\\' . $app . '\\Model\\' . $app . 'User';
        return [
            'route' => $route,
            'model' => $model,
            'layer' => strtolower($parsing['layer'])
        ];
    }

    protected function table()
    {
        $parser = $this->parserData();
        $table = new \Duxravel\Core\UI\Table(new $parser['model']());
        $table->title('用户管理');
        $table->action()->button('添加', $parser['route'] . '.page')->type('dialog');

        $table->filter('用户名', 'username', function ($query, $value) {
            $query->where('username', 'like', '%' . $value . '%');
        })->text('请输入用户名搜索')->quick();

        $table->filter('角色', 'role_id', function ($query, $value) {
            $query->whereHas('roles', function ($query) use ($value) {
                $query->where((new $this->model)->roles()->getTable() . '.role_id', $value);
            });
        })->select(function () {
            return \Duxravel\Core\Model\Role::where('guard', 'admin')->pluck('name', 'role_id')->toArray();
        })->quick();

        $table->column('#', 'user_id')->width(80);
        $table->column('用户名', 'username');
        $table->column('昵称', 'nickname');
        $table->column('角色', 'roles.name');
        $table->column('状态', 'status')->status([
            1 => '正常',
            0 => '禁用'
        ], [
            1 => 'blue',
            0 => 'red'
        ]);

        $column = $table->column('操作')->width(200);
        $column->link('编辑', $parser['route'] . '.page', ['id' => 'user_id'])->type('dialog');
        $column->link('删除', $parser['route'] . '.del', ['id' => 'user_id'])->type('ajax', ['method' => 'post']);

        return $table;
    }

    public function form(int $id = 0)
    {
        $parser = $this->parserData();
        $form = new \Duxravel\Core\UI\Form(new $parser['model']());
        $form->dialog(true);
        $form->setKey('user_id', $id);

        $info = $form->info();
        $ids = [];
        if ($info) {
            $ids = $info->roles()->wherePivot('guard', $parser['layer'])->get()->pluck('role_id')->toArray();
        }
        $form->select('角色', 'role_ids', function () use ($parser) {
            return \Duxravel\Core\Model\Role::where('guard', $parser['layer'])->pluck('name', 'role_id');
        })->multi()->verify([
            'required',
        ], [
            'required' => '请选择角色',
        ])->sort(-1)->value($ids);

        $form->text('用户名', 'username')->verify([
            'required',
            'min:4',
            Rule::unique((new $parser['model'])->getTable())->ignore($id, 'user_id'),
        ], [
            'required' => '请填写用户名',
            'unique' => '用户名不能重复',
            'min' => '用户名不能少于4位',
        ]);

        $form->text('昵称', 'nickname')->verify('required', [
            'required' => '请填写昵称',
        ]);

        $form->password('密码', 'password')->verify('required|min:4', [
            'required' => '请填写密码',
            'min' => '密码不能少于4位',
        ], 'add')->verify('nullable|min:4', [
            'min' => '密码不能少于4位',
        ], 'edit')->value('')->help($id ? '不修改密码请留空' : '');

        $form->radio('状态', 'status', [
            1 => '启用',
            0 => '禁用',
        ]);

        $form->after(function ($formatData, $type, $model) use ($parser) {
            $roleIds = request()->input('role_ids');
            $sync = [];
            foreach ($roleIds as $id) {
                $sync[$id] = ['guard'=> $parser['layer']];
            }
            $model->roles()->sync($sync);
        });

        return $form;
    }

    public function dataSearch()
    {
        return ['nickname', 'username'];
    }

    public function dataField()
    {
        return ['username as name'];
    }

}
