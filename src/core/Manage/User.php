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
        ];
    }

    protected function table()
    {
        $parser = $this->parserData();
        $table = new \Duxravel\Core\UI\Table(new $parser['model']());
        $table->title('用户管理');
        $table->action()->button('添加', $parser['route'] . '.page')->type('dialog');

        $table->column('#', 'user_id')->width(80);
        $table->column('用户名', 'username');
        $table->column('昵称', 'nickname');

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

        $table->filter('用户名', 'username', function ($query, $value) {
            $query->where('username', 'like', '%' . $value . '%');
        })->text('请输入用户名搜索')->quick();


        return $table;
    }

    public function form(int $id = 0)
    {
        $parser = $this->parserData();
        $form = new \Duxravel\Core\UI\Form(new $parser['model']());
        $form->dialog(true);
        $form->setKey('user_id', $id);

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
