<?php

namespace Duxravel\Core\UI;

use Doctrine\DBAL\Schema\View;
use Duxravel\Core\UI\Table\Node;
use Duxravel\Core\UI\Widget\Icon;
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
    protected array $rows = [];
    protected array $map = [];
    protected array $filterParams = [];
    protected array $filterShow = [];
    protected string $ajax = '';
    protected string $key = '';
    protected ?bool $dialog = null;
    protected string $title = '';
    protected array $headerNode = [];
    protected array $footerNode = [];
    protected array $sideNode = [];
    protected string $sortable = '';
    protected bool $tree = false;
    protected int $limit = 20;
    protected array $attr = [];
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
        $this->filtersCallback = Collection::make();

        if (request()->header('x-dialog')) {
            $this->dialog = true;
        }
    }

    /**
     * 设置列
     * @param string $name
     * @param string $label
     * @param null $callback
     * @param false $multi
     * @return Column
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
     * 设置行数据
     * @param \Closure $callback
     * @return $this
     */
    public function row(\Closure $callback): self
    {
        $this->rows[] = $callback;
        return $this;
    }

    /**
     * 设置字段映射
     * @return $this
     */
    public function map(array $map): self
    {
        $this->map = array_merge($this->map, $map);
        return $this;
    }

    /**
     * 筛选参数
     * @param $key
     * @param $value
     * @return $this
     */
    public function filterParams($key, $value): self
    {
        $this->filterParams[$key] = $value;
        return $this;
    }

    /**
     * 筛选展示
     */
    public function filterShow($key, $value): self
    {
        $this->filterShow[$key] = $value;
        return $this;
    }

    /**
     * 自定义头
     * @param string|callable|object $callback
     * @return $this
     */
    public function header($callback): self
    {
        $this->headerNode[] = $callback;
        return $this;
    }

    /**
     * 自定义底部
     * @param string|callable $callback
     * @return $this
     */
    public function footer($callback): self
    {
        $this->footerNode[] = $callback;
        return $this;
    }

    /**
     * 自定义侧边
     * @param $callback
     * @param string $direction
     * @return $this
     */
    public function side($callback, string $direction = 'left'): self
    {
        $this->sideNode[] = [
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
    public function filter(string $name, string $field, $where = true, $default = null): Filter
    {
        $filter = new \Duxravel\Core\UI\Table\Filter($name, $field, $where, $default);
        $filter->setLayout($this);
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
    public function filterType(string $name, callable $where = null): FilterType
    {
        $filterType = new \Duxravel\Core\UI\Table\FilterType($name, $where);
        $filterType->setLayout($this);
        return tap($filterType, function ($value) {
            $this->filtersType->push($value);
        });
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
     * @return $this
     */
    public function tree()
    {
        $this->tree = true;
        return $this;
    }

    /**
     * 表格排序
     * @param string $url
     * @return $this
     */
    public function sortable($url): self
    {
        $this->sortable = $url;
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
        $this->ajax = $url;
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
     * 渲染列配置
     * @return array
     */
    public function renderColumn()
    {
        $columnNode = $this->getColumns()->map(function ($column, $key) {
            if (!Tools::isAuth($column->getAuth())) {
                return null;
            }
            $render = $column->getRender();
            if (!empty($render)) {
                $render['sort'] = $render['sort'] ?? $key;
                return $render;
            }
        })->filter()->sortBy('sort')->values()->toArray();
        return $columnNode;
    }

    /**
     * 渲染表格
     */
    public function render()
    {
        // 扩展节点
        $headerNode = [];
        foreach ($this->headerNode as $vo) {
            $headerNode[] = is_callable($vo) ? $vo() : $vo;
        }
        $footerNode = [];
        foreach ($this->footerNode as $vo) {
            $footerNode[] = is_callable($vo) ? $vo() : $vo;
        }
        // 动作节点
        $actionNode = $this->action ? $this->action()->render() : [];
        // 批处理节点
        $batchNode = $this->batch ? $this->batch()->render() : [];
        // 类型筛选
        $typeNode = $this->filtersType->map(function ($filter, $key) {
            return $filter->render($key);
        })->toArray();
        // 筛选数据
        $filters = $this->filters->map(function ($filter) {
            return $filter->render();
        })->toArray();
        $filterNode = [];
        $quickNode = [];
        foreach ($filters as $vo) {
            if ($vo['quick']) {
                $quickNode[] = $vo['render'];
            } else {
                $filterNode[] = $vo['render'];
            }
        }
        // 表格列节点
        $columnNode = $this->getColumns()->map(function ($column, $key) {
            if (!Tools::isAuth($column->getAuth())) {
                return null;
            }
            $render = $column->getRender();
            if (!empty($render)) {
                $render['sort'] = $render['sort'] ?? $key;
                return $render;
            }
        })->filter()->sortBy('sort')->values()->toArray();

        $keyName = $this->key ?: ($this->model ? $this->model->getKeyName() : '');
        $node = new Node($url ?: url(request()->path() . '/ajax'), $keyName, $this->title);
        $node->dialog((bool)$this->dialog);
        $node->params($this->attr);
        $node->data($this->filterParams, $this->filterShow);
        $node->columns($columnNode);
        $node->sortable($this->sortable);

        $node->type($typeNode);
        $node->quickFilter($quickNode);
        $node->filter($filterNode);
        foreach ($this->sideNode as $vo) {
            $node->side($vo['callback'], $vo['direction']);
        }
        if ($actionNode) {
            $node->action($actionNode);
        }
        if ($batchNode) {
            $node->bath($batchNode);
        }
        return app_success('ok', $node->render());
    }

    /**
     * 数据渲染
     */
    public function renderAjax()
    {
        // 筛选数据
        $this->filters->map(function ($filter) {
            $filter->execute($this->query);
        });

        // 列筛选数据
        if ($this->columns) {
            $this->columns->map(function ($column) {
                if (method_exists($column, 'execute')) {
                    $column->execute($this->query);
                }
            });
        }

        //主键
        $key = $this->key ?: ($this->model ? $this->model->getKeyName() : '');

        $limit = request()->get('limit', $this->limit);

        // 查询列表
        if ($this->query) {
            $data = $this->query;
            if ($this->tree || $this->sortable) {
                $data = $data->paginate(99999)->eloquent();
                $data->setCollection($data->getCollection()->toTree());
            } else {
                $data = $data->paginate($limit)->eloquent();
            }
        } else {
            $data = $this->paginateCollection($this->data, $limit);
            if ($this->tree || $this->sortable) {
                $data->setCollection(collect(Tree::arr2table($data->getCollection()->toArray(), $key, 'parent_id')));
            }
        }
        if ($this->dataCallback) {
            $data->setCollection(call_user_func($this->dataCallback, $data->getCollection()));
        }

        $totalPage = $data->lastPage();
        $page = $data->currentPage();

        // 设置行数据回调
        $this->map[] = $key;

        $columns = [];
        if ($this->columns) {
            $columns = $this->columns->map(function ($column) {
                if (!Tools::isAuth($column->getAuth())) {
                    return null;
                }
                return $column;
            })->filter();
        }
        $resetData = $this->formatData($data, $columns);

        return app_success('ok', [
            'data' => $resetData,
            'totalPage' => $totalPage,
        ]);

    }

    /**
     * @param $data
     * @param $columns
     * @return array
     */
    private function formatData($data, $columns)
    {
        $resetData = [];
        foreach ($data as $vo) {
            $rowData = [];
            if ($this->rows) {
                foreach ($this->rows as $row) {
                    if ($call = call_user_func($row, $vo)) {
                        $rowData = $call;
                    }
                }
            }
            foreach ($columns as $column) {
                if ($colData = $column->getData($vo)) {
                    foreach ($colData as $k => $v) {
                        $rowData[$k] = $v;
                    }
                }
            }
            if ($this->map) {
                foreach ($this->map as $k => $v) {
                    $rowData[is_int($k) ? $v : $k] = is_callable($v) ? call_user_func($v, $vo) : $vo[$v];
                }
            }
            if ($vo['children']) {
                $rowData['children'] = $this->formatData($vo['children'], $columns);
            }
            $resetData[] = $rowData;
        }
        return $resetData;
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
