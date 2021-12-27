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
    public array $fields = [];
    protected ?Collection $columns = null;
    protected ?Collection $filters = null;
    protected ?Collection $filtersType = null;
    protected ?Action $action = null;
    protected ?Batch $batch = null;
    protected array $expand = [];
    protected array $class = [];
    protected array $rows = [];
    protected array $map = [];
    protected array $filterParams = [];
    protected string $url = '';
    protected string $key = '';
    protected ?bool $dialog = null;
    protected string $title = '';
    protected array $headerNode = [];
    protected array $footerNode = [];
    protected array $sideNode = [];
    protected array $pageNode = [];
    protected bool $tree = false;
    protected bool $back = false;
    protected int $limit = 20;
    protected array $attr = [];
    protected $script = [];
    protected $scriptReturn = [];
    protected $scriptData = [];
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
            $this->fields = \Schema::getColumnListing($data->getTable());
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
     * 展开行
     * @param string $title
     * @param array $node
     * @param int $width
     */
    public function expand($title = '', $node = [], $width = 100)
    {
        $this->expand = [
            'title' => $title,
            'width' => $width,
            'vRender:expandedRowRender:rowData' => $node

        ];
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
        $column->setLayout($this);
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
     * @param false $resize
     * @param string $width
     * @return $this
     */
    public function side($callback, string $direction = 'left', bool $resize = false, string $width = '100px'): self
    {
        $this->sideNode[] = [
            'callback' => $callback,
            'direction' => $direction,
            'resize' => $resize,
            'width' => $width
        ];
        return $this;
    }

    /**
     * 自定义page内容
     * @param $callback
     * @param string $direction
     * @return $this
     */
    public function page($callback, string $direction = 'left'): self
    {
        $this->pageNode[] = [
            'callback' => $callback,
            'direction' => $direction
        ];
        return $this;
    }

    /**
     * 设置样式class
     * @return $this
     */
    public function class(string $class): self
    {
        $this->class[] = $class;
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
        if (!isset($this->filterParams['type'])) {
            $this->filterParams('type', request()->get('type', 0));
        }
        $filterType = new \Duxravel\Core\UI\Table\FilterType($name, $where, $this->filterParams['type']);
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
     * 树形状态
     * @return bool
     */
    public function getTree()
    {
        return $this->tree;
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
     * url数据
     * @param string $url
     * @return $this
     */
    public function url(string $url = ''): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * 获取Url
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $content
     * @param string $return
     * @return $this
     */
    public function script($content = '', $return = ''): self
    {
        $this->script[] = $content;
        $this->scriptReturn[] = $return;
        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function scriptData($data): self
    {
        $this->scriptData = array_merge($this->scriptData, $data);
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
            $render = $column->getRender();
            if (!empty($render)) {
                $render['sort'] = $render['sort'] ?? $key;
                return $render;
            }
        })->filter()->sortBy('sort')->values()->toArray();

        $keyName = $this->key ?: ($this->model ? $this->model->getKeyName() : '');
        $node = new Node($this->url ?: url(request()->path() . '/ajax'), $keyName, $this->title);
        $node->class(implode(' ', $this->class));
        $node->params($this->attr);
        $node->data($this->filterParams);
        $node->columns($columnNode);
        $node->expand($this->expand);

        foreach ($this->script as $key => $value) {
            $node->script($value, $this->scriptReturn[$key]);
        }
        if ($this->scriptData) {
            $node->scriptData($this->scriptData);
        }

        $node->type($typeNode);
        $node->quickFilter($quickNode);
        $node->filter($filterNode);
        foreach ($this->sideNode as $vo) {
            $node->side($vo['callback'], $vo['direction'], $vo['resize'], $vo['width']);
        }
        foreach ($this->pageNode as $vo) {
            $node->page($vo['callback'], $vo['direction'], $vo['resize'], $vo['width']);
        }


        $node->header($headerNode);
        $node->footer($footerNode);

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
        $this->filtersType->map(function ($filter, $key) {
            $filter->execute($this->query, $key);
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
            if ($this->tree) {
                $data = $data->paginate(99999)->eloquent();
                $data->setCollection($data->getCollection()->toTree());
            } else {
                $data = $data->paginate($limit)->eloquent();
            }
        } else {
            $data = $this->paginateCollection($this->data, $limit);
            if ($this->tree) {
                $data->setCollection(collect(Tree::arr2table($data->getCollection()->toArray(), $key, 'parent_id')));
            }
        }
        if ($this->dataCallback) {
            $dataCallback = call_user_func($this->dataCallback, $data->getCollection());
            $data->setCollection($dataCallback);
        }

        $totalPage = $data->lastPage();
        $page = $data->currentPage();
        $total = $data->total();


        $columns = [];
        if ($this->columns) {
            $columns = $this->columns->map(function ($column) {
                return $column;
            })->filter();
        }


        // 设置行数据回调
        $this->map[] = $key;

        // 排序自动设置key
        if ($this->tree) {
            $this->map['key'] = $key;
        }

        $resetData = $this->formatData($data, $columns);

        return app_success('ok', [
            'data' => $resetData,
            'total' => $total,
            'pageSize' => $limit,
            'totalPage' => $totalPage,
        ]);
    }

    /**
     * 渲染行数据
     */
    public function renderRowData(Collection $data, bool $tree = true)
    {
        if ($this->dataCallback) {
            $data = call_user_func($this->dataCallback, $data);
        }
        $key = $this->key ?: ($this->model ? $this->model->getKeyName() : '');
        $columns = [];
        if ($this->columns) {
            $columns = $this->columns->map(function ($column) {
                return $column;
            })->filter();
        }
        // 设置行数据回调
        $this->map[] = $key;

        // 排序自动设置key
        if ($this->tree) {
            $this->map['key'] = $key;
        }
        $resetData = $this->formatData($data, $columns, $tree);
        return $resetData;

    }

    /**
     * @param $data
     * @param $columns
     * @return array
     */
    private function formatData($data, $columns, $tree = true)
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
                    $rowData[is_int($k) ? str_replace(['.', '->'], '_', $v) : $k] = is_callable($v) ? call_user_func($v, $vo) : Tools::parsingArrData($vo, $v);
                }
            }
            if ($vo['children'] && $tree) {
                $rowData['children'] = $this->formatData($vo['children'], $columns, $tree);
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
