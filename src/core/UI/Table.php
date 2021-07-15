<?php

namespace Duxravel\Core\UI;

use Doctrine\DBAL\Schema\View;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Duxravel\Core\Model\ModelAgent;
use Duxravel\Core\Util\Tree;
use Duxravel\Core\UI\Table\Action;
use Duxravel\Core\UI\Table\Batch;
use Duxravel\Core\UI\Table\Column;
use Duxravel\Core\UI\Table\Filter;
use Duxravel\Core\UI\Table\FilterType;

/**
 * 表格UI
 * Class Table
 * @package Duxravel\Core\UI
 */
class Table
{
    public ?Eloquent $model = null;
    public ?ModelAgent $query = null;
    protected ?Collection $columns = null;
    protected ?Collection $filters = null;
    protected ?Collection $filtersType = null;
    protected ?Action $action = null;
    protected ?Batch $batch = null;
    protected array $filterParams = [];
    protected string $ajax = '';
    protected string $key = '';
    protected ?bool $dialog = null;
    protected string $title = '';
    protected array $headerHtml = [];
    protected array $toolsHtml = [];
    protected array $footerHtml = [];
    protected array $sideHtml = [];
    protected string $tree = '';
    protected string $sortable = '';
    protected int $limit = 20;
    protected array $attr = [];
    protected array $class = [];
    protected array $style = [];
    protected array $assign = [];
    protected ?\Closure $dataCallback = null;
    protected $data;

    /**
     * Table constructor.
     * @param  $data
     */
    public function __construct($data)
    {
        if ($data instanceof Eloquent) {
            $this->model = $data;
            $this->query = new ModelAgent($data);
        } else {
            $this->data = $data;
        }
        $this->columns = Collection::make();
        $this->filters = Collection::make();
        $this->filtersType = Collection::make();
    }

    /**
     * 设置列
     * @param string $name
     * @param string $label
     * @param null $callback
     * @return Column
     * @throws \Exception
     */
    public function column(string $name = '', string $label = '', $callback = null): Column
    {
        //关联模型
        if ($this->model && \Str::contains($label, '.')) {
            return $this->joinColumn($name, $label, $callback);
        }

        //数组对象
        if (\Str::contains($label, '->')) {
            $label = str_replace('->', '.', $label);
            return $this->addColumn($name, $label, $callback);
        }

        //是否关联模型
        if ($this->model && $this->hasRelationColumn($label)) {
            $this->query->with($label);
            return $this->addColumn($name, $label, $callback)->setRelation($label);
        }
        return $this->addColumn($name, $label, $callback);
    }

    /**
     * 添加列参数
     * @param $name
     * @param $label
     * @param $callback
     * @return Column
     */
    protected function addColumn($name, $label, $callback): Column
    {
        $column = new Column($name, $label, $callback);
        return tap($column, function ($value) {
            $this->columns->push($value);
        });
    }

    /**
     * 判断关联模型
     * @param $relation
     * @return bool
     */
    protected function hasRelationColumn($relation): bool
    {
        //$model = $this->model()->eloquent();
        if (!method_exists($this->model, $relation)) {
            return false;
        }
        if (!$this->model->{$relation}() instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
            return false;
        }
        return true;
    }

    /**
     * 关联模型
     * @param $name
     * @param $label
     * @param $callback
     * @return Column
     */
    protected function joinColumn($name, $label, $callback): Column
    {
        [$relation, $field] = explode('.', $label, 2);
        $this->query->with($relation);
        return $this->addColumn($name, str_replace('->', '.', $field), $callback)->setRelation($relation);
    }

    /**
     * 获取列集合
     * @return Collection
     */
    protected function getColumns(): Collection
    {
        return $this->columns;
    }

    /**
     * 筛选参数
     * @param $name
     * @param $value
     * @return $this
     */
    public function filterParams($name, $value): self
    {
        $this->filterParams[] = [
            'name' => $name,
            'value' => $value
        ];
        return $this;
    }

    /**
     * 自定义头html
     * @param string|callable|object $callback
     * @return $this
     */
    public function header($callback): self
    {
        $this->headerHtml[] = $callback;
        return $this;
    }

    /**
     * 自定义工具html
     * @param string|callable $callback
     * @return $this
     */
    public function tools($callback): self
    {
        $this->toolsHtml[] = $callback;
        return $this;
    }

    /**
     * 自定义工具html
     * @param string|callable $callback
     * @return $this
     */
    public function footer($callback): self
    {
        $this->footerHtml[] = $callback;
        return $this;
    }

    /**
     * 自定义侧边html
     * @param $callback
     * @param string $direction
     * @return $this
     */
    public function side($callback, string $direction = 'left'): self
    {
        $this->sideHtml[] = [
            'callback' => $callback,
            'direction' => $direction
        ];
        return $this;
    }

    /**
     * 设置筛选条件
     * @param string $name
     * @param string $field
     * @param callable|string|bool $where
     * @return Filter
     */
    public function filter(string $name, string $field, $where = true): Filter
    {
        $filter = new \Duxravel\Core\UI\Table\Filter($name, $field, $where);
        return tap($filter, function ($value) {
            $this->filters->push($value);
        });
    }


    /**
     * 筛选类型
     * @param string $name
     * @param callable|null $where
     * @param string|null $url
     * @return FilterType
     */
    public function filterType(string $name, callable $where = null, string $url = null): FilterType
    {
        $filterType = new \Duxravel\Core\UI\Table\FilterType($name, $where, $url);
        $filterType->setLayout($this);
        $this->filtersType->push($filterType);
        return $filterType;

    }

    /**
     * 设置动作
     * @return Action
     */
    public function action(): Action
    {
        if (!$this->action) {
            $this->action = new Action();
        }
        return $this->action;
    }

    /**
     * 批量操作
     * @return Batch
     */
    public function batch(): Batch
    {
        if (!$this->batch) {
            $this->batch = new Batch();
        }
        return $this->batch;
    }

    /**
     * 树形表格
     * @param string $field
     * @param string $sortable
     * @return $this
     */
    public function tree(string $field = 'parent_id', string $sortable = ''): self
    {
        $this->tree = $field;
        $this->sortable = $sortable;
        return $this;
    }

    /**
     * 表格排序
     * @param string $sortable
     * @return $this
     */
    public function sortable(string $sortable = ''): self
    {
        $this->sortable = $sortable;
        return $this;
    }

    /**
     * 表格标题
     * @param $title
     * @return $this
     */
    public function title($title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * 分页数量
     * @param $num
     * @return self
     */
    public function limit($num): self
    {
        $this->limit = $num;
        return $this;
    }

    /**
     * 模型对象
     */
    public function model(): ModelAgent
    {
        return $this->query;
    }

    /**
     * 模型对象
     * @return Eloquent
     */
    public function modelElo(): ?Eloquent
    {
        return $this->model;
    }

    /**
     * 设置样式
     * @param $name
     * @param $value
     * @return $this
     */
    public function style($name, $value): self
    {
        $this->style[$name] = $value;
        return $this;
    }

    /**
     * 设置样式类
     * @param string $class
     * @return $this
     */
    public function class(string $class): self
    {
        $this->class[] = $class;
        return $this;
    }

    /**
     * 设置附加属性
     * @param $name
     * @param $value
     * @return $this
     */
    public function attr($name, $value): self
    {
        $this->attr[$name] = $value;
        return $this;
    }


    /**
     * 设置表格主键
     * @param $key
     * @return $this
     */
    public function key($key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * 弹窗
     * @param bool $status
     * @return $this
     */
    public function dialog($status = true): self
    {
        $this->dialog = $status;
        return $this;
    }

    /**
     * ajax表单
     * @param string $url
     * @return $this
     */
    public function ajax(string $url = ''): self
    {
        $this->ajax = $url ?: route(\Str::beforeLast(request()->route()->getName(), '.') . '.' . 'ajax');
        return $this;
    }

    /**
     * 数据导出
     * @param callable $callback
     */
    public function export(callable $callback)
    {
        // 设置筛选信息
        $this->filters->map(function ($filter) {
            return $filter->render();
        });
        // 设置筛选类型
        $this->filtersType->map(function ($filter, $key) {
            return $filter->render($key);
        });
        // 查询导出数据
        $data = $this->query->eloquent()->get();
        // 执行渲染输出
        $export = new \Duxravel\Core\UI\Table\Export();
        $callback($export);
        $export->render($data);
    }

    /**
     * 指定模板变量
     * @param string $name
     * @param null $value
     * @return $this
     */
    public function assign(string $name, $value = null): self
    {
        $this->assign[$name] = $value;
        return $this;
    }

    /**
     * 数据回调
     * @param callable $callback
     * @return $this
     */
    public function dataCallback(callable $callback): self
    {
        $this->dataCallback = $callback;
        return $this;
    }

    /**
     * 渲染表格
     */
    public function render()
    {
        // 设置筛选信息
        $filters = $this->filters->map(function ($filter) {
            return $filter->render();
        })->toArray();

        // 设置渲染类型
        $filterType = $this->filtersType->map(function ($filter, $key) {
            return $filter->render($key);
        })->implode('');

        $filter = [];
        $filterStatus = false;
        $quick = [];
        foreach ($filters as $vo) {
            if ($vo['quick']) {
                $quick[] = $vo['html'];
            } else {
                $filter[] = $vo['html'];
                if (!$filterStatus && $vo['status']) {
                    $filterStatus = true;
                }
            }
        }

        // 设置样式
        $style = Tools::toStyle($this->style, true);
        $class = Tools::toClass($this->class, true);
        $attr = Tools::toAttr($this->attr);

        // 设置操作
        $actions = $this->action ? $this->action()->render() : [];

        // 批量操作
        $batch = $this->batch ? $this->batch()->render() : [];
        $params = request()->all();

        // 弹窗布局
        if ($this->dialog === null) {
            $this->dialog = (bool)request()->get('dialog');
        }

        // ajax表单
        $ajax = $this->ajax;

        if (!$ajax) {
            $table = $this->renderTable($filters);
            $data = $table['data'];
            $thead = $table['thead'];
            $tbody = $table['tbody'];
        } else {
            $data = null;
            $thead = [];
            $tbody = [];
        }


        //html解析
        $headerHtml = [];
        foreach ($this->headerHtml as $vo) {
            $headerHtml[] = is_callable($vo) ? $vo() : $vo;
        }
        $toolsHtml = [];
        foreach ($this->toolsHtml as $vo) {
            $toolsHtml[] = is_callable($vo) ? $vo() : $vo;
        }
        $footerHtml = [];
        foreach ($this->footerHtml as $vo) {
            $footerHtml[] = is_callable($vo) ? $vo() : $vo;
        }

        $sideLeftHtml = [];
        $sideRightHtml = [];
        foreach ($this->sideHtml as $vo) {
            if ($vo['direction'] === 'left') {
                $sideLeftHtml[] = is_callable($vo['callback']) ? $vo['callback']() : $vo['callback'];
            } else {
                $sideRightHtml[] = is_callable($vo['callback']) ? $vo['callback']() : $vo['callback'];
            }
        }

        $assign = [
            'ajax' => $ajax ? $ajax . ($params ? (strpos($ajax, '?') === false ? '?' : '&') . http_build_query($params) : '') : null, // ajax表格
            'pages' => $data && $data->lastPage() > 1 ? $data->withQueryString()->links('vendor.duxphp.duxravel-app.src.core.UI.View.table-pages') : '', // 分页html
            'thead' => $thead, // 表头
            'tbody' => $tbody, // 表数据
            'style' => $style, // 样式
            'class' => $class, // 样式类
            'attr' => $attr, // 附加数据
            'quick' => $quick, // 快速筛选
            'filter' => $filter, // 扩展筛选
            'filterType' => $filterType, // 类型筛选
            'filterParams' => $this->filterParams, // 筛选参数
            'filterStatus' => $filterStatus, // 筛选开关
            'actions' => $actions, // 动作
            'batch' => $batch, // 批量操作
            'headerHtml' => $headerHtml, // 头部html
            'toolsHtml' => $toolsHtml, // 工具html
            'footerHtml' => $footerHtml, // 底部html
            'sideLeftHtml' => $sideLeftHtml, // 侧边栏html
            'sideRightHtml' => $sideRightHtml, // 右侧边栏html
            'title' => $this->title, // 表格标题
            'tree' => $this->tree, // 树形表格
            'sortable' => $this->sortable, // 表格排序
            'dialog' => $this->dialog
        ];
        $assign = array_merge($assign, $this->assign);

        return (new \Duxravel\Core\Util\View('vendor.duxphp.duxravel-app.src.core.UI.View.table', $assign))->render($this->dialog ? 'dialog' : 'base');
    }

    /**
     * 表格渲染
     * @param $filters
     * @return array
     */
    public function renderTable($filters): array
    {
        // 筛选条件
        foreach ($filters as $vo) {
            if ($vo['status']) {
                if ($vo['where'] instanceof \Closure) {
                    $vo['where']($this->query, $vo['value'], $vo['data']);
                } elseif ($vo['where'] !== false) {
                    $this->query->where(is_string($vo['where']) ? $vo['where'] : $vo['field'], $vo['value']);
                }
            }
        }

        //主键
        $key = $this->key ?: ($this->model ? $this->model->getKeyName() : '');

        // 查询列表
        if ($this->query) {
            $data = $this->query;
            if ($this->tree) {
                $data = $data->paginate(99999)->eloquent();
                $data->setCollection($data->getCollection()->toTree());
            } else {
                $data = $data->paginate($this->limit)->eloquent();
            }
        } else {
            foreach ($filters as $vo) {
                if ($vo['status'] && $vo['where'] instanceof \Closure) {
                    call_user_func($vo['where'], $this->data, $vo['value'], $vo['data']);
                }
            }
            $data = $this->paginateCollection($this->data, $this->limit);
            if ($this->tree) {
                $data->setCollection(collect(Tree::arr2table($data->getCollection()->toArray(), $key, $this->tree)));
            }
        }
        if ($this->dataCallback) {
            $data->setCollection(call_user_func($this->dataCallback, $data->getCollection()));
        }

        // 设置表格信息
        $columns = $this->getColumns()->map(function ($column) {
            if (!Tools::isAuth($column->getAuth())) {
                return null;
            }
            return $column;
        })->filter();
        $thead = $columns->map(function ($column, $key) {
            $header = $column->getHeader();
            if (!empty($header)) {
                $header['sort'] = $header['sort'] ?? $key;
                return (object)$header;
            }
        })->filter()->sortBy('sort');

        $tbody = $this->tbody($data, $columns, $key);

        return [
            'thead' => $thead,
            'tbody' => $tbody,
            'data' => $data,
            'key' => $key
        ];
    }

    /**
     * Ajax数据输出
     */
    public function renderAjax()
    {
        $filters = $this->filters->map(function ($filter) {
            return $filter->render();
        })->toArray();
        $table = $this->renderTable($filters);
        $data = $table['data'];
        $thead = $table['thead'];
        $tbody = $table['tbody'];
        $totalPage = $data->lastPage();
        $page = $data->currentPage();

        $assign = [
            'thead' => $thead,
            'tbody' => $tbody,
            'batch' => (bool)$this->batch
        ];
        return app_success('ok', [
            'html' => (new \Duxravel\Core\Util\View('vendor.duxphp.duxravel-app.src.core.UI.View.table-ajax', $assign))->render(null)->render(),
            'totalPage' => $totalPage,
            'page' => $page
        ]);
    }


    /**
     * 渲染表格内容
     * @param $data
     * @param $columns
     * @param $primaryKey
     * @return array
     */
    public function tbody($data, $columns, $primaryKey)
    {
        $tbody = [];
        foreach ($data as $index => $vo) {
            $column = $columns->map(function ($column, $key) use ($vo) {
                $render = $column->render($vo);
                if (!empty($render)) {
                    $render['sort'] = $render['sort'] ?? $key;
                    return (object)$render;
                }
            })->filter()->sortBy('sort');
            $array = [
                'column' => $column,
                'json' => $column->pluck('original', 'name')->toJson(),
                'key' => $vo[$index],
                'id' => $vo[$primaryKey]
            ];
            if ($vo->children) {
                $array['children'] = $this->tbody($vo->children, $columns, $primaryKey);
            }
            $tbody[] = $array;
        }
        return $tbody;
    }

    /**
     * 集合分页
     * @param $collection
     * @param $perPage
     * @param string $pageName
     * @param null $fragment
     * @return LengthAwarePaginator
     */
    protected function paginateCollection($collection, $perPage, $pageName = 'page', $fragment = null): LengthAwarePaginator
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage($pageName);
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();
        parse_str(request()->getQueryString(), $query);
        unset($query[$pageName]);
        return new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'pageName' => $pageName,
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $query,
                'fragment' => $fragment
            ]
        );
    }

}
