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

    /**
     * 事件名称
     * @return false|string
     */
    protected function eventName()
    {
        return get_called_class();
    }

    /**
     * table刷新事件
     * @return array
     */
    protected function tableReload()
    {
        $eventName = md5($this->eventName());
        return (new \Duxravel\Core\UI\Script())->add("dux.event.event.emit('table-action-{$eventName}', 'reload')")->render();
    }

    /**
     * table调到指定页
     * @param $page
     * @return array
     */
    protected function tableToPage($page = 1)
    {
        $eventName = md5($this->eventName());
        return (new \Duxravel\Core\UI\Script())->add("dux.event.event.emit('table-action-{$eventName}', 'to-page',{$page})")->render();
    }

    public function index()
    {
        $table = $this->table();
        event(new ManageTable($this->eventName(), $table));
        return $table->render();
    }

    public function ajax()
    {
        $table = $this->table();
        event(new ManageTable($this->eventName(), $table));
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
        event(new ManageForm($this->eventName(), $form));
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
        event(new ManageForm($this->eventName(), $form));
        $data = $form->save();
        if ($data instanceof Collection && method_exists($this, 'storeData')) {
            $data = $this->storeData($data, $id);
        }

        $data = [];
        if (method_exists($this, 'table')) {
            if (method_exists($this, 'saveEvent')) {
                $data = $this->saveEvent($this->table(), $form, $this->eventName(), $id ? 'edit' : 'add');
            } else {
                $data = $form->callbackEvent($this->table(), $this->eventName(), $id ? 'edit' : 'add');
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

        event(new ManageDel($this->eventName(), $id));
        if ($this->model) {
            $info = $this->model::find($id);
            if(empty($info)){
                app_error('无效的数据');
            }
            $status = $info->delete();
        }
        if (!$status) {
            DB::rollBack();
            app_error('删除记录失败');
        }
        DB::commit();

        $data = [];
        if (method_exists($this, 'delEvent')) {
            $data = $this->delEvent($this->model ? $info : $id, $this->eventName());
        } else {
            $data = (new Event($this->eventName()))->add('del', $id)->render();
        }

        return app_success('删除记录成功', $data);
    }

    public function batchDel($ids = 0){
        if (!$ids) {
            $ids = request()->input('ids');
        }
        if (!$ids) {
            app_error('删除参数错误');
        }
        if(!is_array($ids)){
            $ids = explode(',',$ids);
        }
        DB::beginTransaction();
        $status = false;
        if (method_exists($this, 'delData')) {
            foreach ($ids as $id){
                $status = $this->delData($id);
                if (!$status) {
                    DB::rollBack();
                    app_error('删除记录失败');
                }
            }
        }
        if ($this->model) {
            $status = $this->model::destroy($ids);
        }
        if (!$status) {
            DB::rollBack();
            app_error('删除记录失败');
        }
        DB::commit();

        return app_success('删除记录成功',$this->tableReload());
    }

    public function export()
    {
        $table = $this->table();
        event(new ManageExport($this->eventName(), $table));
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
        event(new ManageRecovery($this->eventName(), $id));
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
        event(new ManageClear($this->eventName(), $id));
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
        event(new ManageStatus($this->eventName(), $id));
        DB::beginTransaction();
        try{
            if (method_exists($this, 'statusData')) {
                $status = $this->statusData($id,$field,$value);
                if (!$status) {
                    app_error('更改状态失败');
                }
            }
            $model = $this->model::find($id);
            $model->{$field} = $value;
            $model->save();

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }

        $form = new Form($model);
        $form->info = $model;
        $form->modelId = $model->getKey();
        $data = [];
        if (method_exists($this, 'table')) {
            if (method_exists($this, 'saveEvent')) {
                $data = $this->saveEvent($this->table(), $form, $this->eventName(),  'edit');
            } else {
                $data = $form->callbackEvent($this->table(), $this->eventName(),  'edit');
            }
        }

        $action = $data ? '' : "routerPush:";

        return app_success('更改状态成功', $data,$action);
    }

    public function data()
    {
        $name = request()->get('query');
        $limit = request()->get('limit', 0);
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
                $data = $data->orderByRaw(DB::raw("FIELD($key, $ids) desc"));
            }
        }

        if (method_exists($this, 'dataOrder')) {
            $data = $this->dataOrder($data);
        }else{
            $data->orderBy($key);
        }

        if (method_exists($this, 'dataWhere')) {
            $data = $this->dataWhere($data);
        }

        $field = ['name'];
        if (method_exists($this, 'dataField')) {
            $field = $this->dataField();
        }
        $field[] = $key . ' as id';
        $retData = [];
        if($limit){
            $paginate = $data->paginate($limit, $field);
            $data = $paginate->getCollection();

            $retData['total'] = $paginate->total();
            $retData['pageSize'] = $paginate->perPage();
            $retData['totalPage'] = $paginate->lastPage();
        }else{
            $data = $data->get($field);
        }

        if (method_exists($this, 'dataCallback')) {
            $data = $this->dataCallback($data);
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
        foreach ($data as &$item) {
            if ($manageUrl) {
                $item['manage_url'] = $this->dataManageUrl($item);
            }
            if ($infoUrl) {
                $item['info_url'] = $this->dataInfoUrl($item);
            }
        }

        $retData['data'] = $data;
        return app_success('ok', $retData);
    }

}
