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
        $column->link('删除', $parser['route'] . '.del')->type('ajax')->data(['type' => 'post']);
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

        $form->script(function () {
            return <<<JS
                    $('[auth]').on('click', '[auth-app]', function () {
                        if($(this).prop('checked')) {
                            $(this).parents('[auth]').find('[auth-item]').prop('checked', true)
                        }else {
                            $(this).parents('[auth]').find('[auth-item]').prop('checked', false)
                        }
                    });
                    $('[auth]').on('click', '[auth-group]', function () {
                        if($(this).prop('checked')) {
                            $(this).parents('[group]').find('[auth-item]').prop('checked', true)
                        }else {
                            $(this).parents('[group]').find('[auth-item]').prop('checked', false)
                        }
                    });
                JS;
        });

        $form->before(function ($data, $type, $model) {
            $model->purview = request()->input('purview');
        });

        return $form;
    }

    public function formInner($form, $info)
    {
        $form->text('角色名', 'name')->verify([
            'required',
            'min:2',
        ], [
            'required' => '请填写角色名',
            'min' => '用户名不能少于2位',
        ]);
        $form->html('角色权限', function () use ($info) {
            $parsing = app_parsing();
            $purview = $info->purview ?: [];
            $data = module('Common.Service.Auth')->getAuthAll(strtolower($parsing['layer']));
            $html = [];
            foreach ($data as $app) {
                $html[] = '
                        <div class="card mb-3" auth>
                        <div class="card-header"><label class="mb-0"><input type="checkbox" auth-app  class="form-check-input align-middle align-text-bottom"> ' . $app['name'] . '</label></div>
                        <div class="card-body pt-0 pb-0">';
                foreach ($app['group'] as $group) {
                    $html[] = '<div class="mt-4 mb-4" group><div class="text-black-50 mb-2"><label class="mb-0"><input  auth-group type="checkbox"  class="form-check-input align-middle align-text-bottom"> ' . $group['name'] . '</label></div><div class=" d-flex flex-wrap mb-2">';
                    foreach ($group['list'] as $vo) {
                        $html[] = '<label class="form-check-label" style="width: 100px;"><input auth-item type="checkbox"  ' . (in_array($vo['value'], $purview) ? 'checked' : '') . ' class="form-check-input align-middle align-text-bottom" value="' . $vo['value'] . '" name="purview[]" /> ' . $vo['name'] . '</label>';
                    }
                    $html[] = '
                            </div></div>
                        ';
                }
                $html[] = '</div></div>';
            }
            return implode('', $html);
        })->help('请选择权限，如果不选择任何权限则默认拥有所有权限', true);
        return $form;
    }


}
