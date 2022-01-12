<?php

namespace Duxravel\Core\Manage;

use Duxravel\Core\Events\ManageTable;
use Duxravel\Core\Events\ManageForm;
use Duxravel\Core\Events\ManageStatus;
use Duxravel\Core\Events\ManageClear;
use Duxravel\Core\Events\ManageRecovery;
use Duxravel\Core\Events\ManageExport;
use Duxravel\Core\Events\ManageDel;
use Duxravel\Core\UI\Event;
use Duxravel\Core\UI\Form;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * 管理端口扩展功能接口
 * Trait Expend
 * @package Duxravel\Core\Controller
 * @method \Duxravel\Core\UI\Table table()
 * @method \Duxravel\Core\UI\Form form($id = 0)
 * @method saveEvent($table, Form $form, $class, $type)
 * @method storeData($data, $id)
 * @method delData($id = 0)
 * @method clearData($id, $info)
 * @method dataSearch()
 * @method dataWhere($query)
 * @method dataField()
 * @method dataManageUrl($item)
 * @method dataInfoUrl($item)
 * @method exportData($export)
 */
trait Expend
{

    public string $model;
    public ?string $indexUrl = null;

    public function index()
    {
        $table = $this->table();
        event(new ManageTable(get_called_class(), $table));
        return $table->render();
    }

    public function ajax()
    {
        $table = $this->table();
        event(new ManageTable(get_called_class(), $table));
        return $table->renderAjax();
    }

    public function page($id = 0)
    {
        if ($id) {
            $this->can('edit');
        } else {
            $this->can('add');
        }
        $form = $this->form($id);
        if ($id && $form->modelElo()) {
            $form->setKey($form->modelElo()->getKeyName(), $id);
        }
        event(new ManageForm(get_called_class(), $form));
        return $form->render();
    }

    public function save($id = 0)
    {
        if ($id) {
            $this->can('edit');
        } else {
            $this->can('add');
        }
        $form = $this->form($id);
        if ($id && $form->modelElo) {
            $form->setKey($form->modelElo()->getKeyName(), $id);
        }
        event(new ManageForm(get_called_class(), $form));
        $data = $form->save();
        if ($data instanceof Collection && method_exists($this, 'storeData')) {
            $data = $this->storeData($data, $id);
        }

        $data = [];
        if (method_exists($this, 'table')) {
            $data = $form->callbackEvent($this->table(), get_called_class(), $id ? 'edit' : 'add');
        }
        if (method_exists($this, 'table')) {
            if (method_exists($this, 'saveEvent')) {
                $data = $this->saveEvent($this->table(), $form, get_called_class(), $id ? 'edit' : 'add');
            } else {
                $data = $form->callbackEvent($this->table(), get_called_class(), $id ? 'edit' : 'add');
            }
        }

        if ($this->indexUrl === null) {
            if ($form->getDialog()) {
                $action = $data ? '' : "routerPush:";
            } else {
                $action = '/' . \Str::beforeLast(request()->path(), '/save');
            }
        } else {
            $action = $this->indexUrl;
        }

        return app_success('保存记录成功', $data, $action);
    }

    public function del($id = 0)
    {
        if (!$id) {
            $id = request()->input('id');
        }
        if (!$id) {
            app_error('删除参数错误');
        }
        DB::beginTransaction();
        $status = false;
        if (method_exists($this, 'delData')) {
            $status = $this->delData($id);
            if (!$status) {
                DB::rollBack();
                app_error('删除记录失败');
            }
        }
        event(new ManageDel(get_called_class(), $id));
        if ($this->model) {
            $status = $this->model::destroy($id);
        }
        if (!$status) {
            DB::rollBack();
            app_error('删除记录失败');
        }
        DB::commit();

        $action = '';
        //$action = "routerPush:";

        return app_success('删除记录成功', (new Event(get_called_class()))->add('del', $id)->render(), $action);
    }

    public function export()
    {
        $table = $this->table();
        event(new ManageExport(get_called_class(), $table));
        if (!method_exists($this, 'exportData')) {
            app_error('', 404);
        }
        $table->export(function ($export) {
            return $this->exportData($export);
        });
    }

    public function recovery($id = 0)
    {
        if (!$id) {
            $id = request()->input('id');
        }
        if (!$id) {
            app_error('参数错误');
        }
        event(new ManageRecovery(get_called_class(), $id));
        if ($this->model) {
            $this->model::withTrashed()->find($id)->restore();
        }
        return app_success('恢复记录成功');
    }

    public function clear($id = 0)
    {
        if (!$id) {
            $id = request()->input('id');
        }
        if (!$id) {
            app_error('参数错误');
        }
        DB::beginTransaction();
        $info = $this->model::withTrashed()->find($id);
        if (method_exists($this, 'clearData')) {
            $status = $this->clearData($id, $info);
            if (!$status) {
                DB::rollBack();
                app_error('删除记录失败');
            }
        }
        event(new ManageClear(get_called_class(), $id));
        if ($this->model) {
            $info->forceDelete();
        }
        DB::commit();
        return app_success('删除记录成功');
    }

    public function status($id = 0)
    {
        if (!$id) {
            $id = request()->input('id');
        }
        if (!$id) {
            app_error('参数错误');
        }
        $field = request()->input('field', 'status');
        $value = request()->input($field);
        if (!$id || !$field) {
            app_error('状态参数传递错误');
        }
        $model = $this->model::find($id);
        $model->{$field} = $value;
        $model->save();
        event(new ManageStatus(get_called_class(), $id));
        return app_success('更改状态成功');
    }


    public function data()
    {
        $name = request()->get('query');
        $limit = request()->get('limit', 10);
        $id = request()->get('id');
        $data = new $this->model();
        $key = $data->getKeyName();
        if ($name) {
            $nameKey = [];
            if (method_exists($this, 'dataSearch')) {
                $nameKey = $this->dataSearch();
            }
            $data = $data->where(function ($query) use ($nameKey, $name) {
                foreach ($nameKey as $vo) {
                    $query->orWhere($vo, 'like', "%{$name}%");
                }
            });

        }
        if ($id) {
            $ids = !is_array($id) ? explode(',', $id) : $id;
            $ids = array_filter($ids);
            if ($ids) {
                $ids = implode(',', $ids);
                $data = $data->orderByRaw(DB::raw("FIELD($key, $ids) desc"))->orderBy($key, 'desc');
            }
        }

        if (method_exists($this, 'dataWhere')) {
            $data = $this->dataWhere($data);
        }

        $field = ['name'];
        if (method_exists($this, 'dataField')) {
            $field = $this->dataField();
        }
        $field[] = $key . ' as id';
        $data = $data->paginate($limit, $field);

        $totalPage = $data->lastPage();
        $total = $data['total'];

        if (method_exists($this, 'dataCallback')) {
            $data->setCollection($this->dataCallback($data->getCollection()));
        }

        $data = $data->toArray();

        $manageUrl = false;
        if (method_exists($this, 'dataManageUrl')) {
            $manageUrl = true;
        }
        $infoUrl = false;
        if (method_exists($this, 'dataInfoUrl')) {
            $infoUrl = true;
        }
        foreach ($data['data'] as &$item) {
            if ($manageUrl) {
                $item['manage_url'] = $this->dataManageUrl($item);
            }
            if ($infoUrl) {
                $item['info_url'] = $this->dataInfoUrl($item);
            }
        }

        return app_success('ok', [
            'data' => $data['data'],
            'total' => $total,
            'pageSize' => $limit,
            'totalPage' => $totalPage,
        ]);
    }
}
