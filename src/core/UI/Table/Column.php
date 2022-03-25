<?php

namespace Duxravel\Core\UI\Table;

use Exception;
use Illuminate\Support\Collection;
use Duxravel\Core\UI\Widget\Link;
use Duxravel\Core\UI\Table;
use Duxravel\Core\UI\Tools;

/**
 * 表格列
 * Class Column
 * @method static Column\Hidden hidden()
 * @method static Column\Progress progress(string $color = 'default')
 * @method static Column\Status status(array $map, array $color, string $type = 'badge')
 * @method static Column\Chart chart(int $day = 7, string $has = 'viewsData', string $key = 'pv', string $name = '访问量', string $type = 'area')
 * @method static Column\Tags tags(array $map, array $color)
 * @method static Column\Toggle toggle(string $field, string $url, array $params = [])
 * @method static Column\Input input(string $field, $url, array $params = [])
 * @package Duxravel\Core\UI\Table
 */
class Column
{

    protected string $name;
    protected string $label = '';
    protected ?\Closure $callback = null;
    protected array $node = [];
    protected array $attr = [];
    protected array $function = [];
    protected array $children = [];
    protected $width = '';
    protected $align = '';
    protected $fixed = '';
    protected $class = [];
    protected ?int $colspan = null;
    protected ?\Closure $show = null;
    protected ?int $sort = null;
    protected $sorter = null;
    protected $layout;
    protected $relation;
    protected $model;
    protected $element;
    protected $extend;

    /**
     * Column constructor.
     * @param string $name
     * @param string $label
     * @param null   $callback
     */
    public function __construct(string $name = '', string $label = '', $callback = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->callback = $callback;
    }

    /**
     * 设置父级对象
     * @param Table $layout
     */
    public function setLayout(Table $layout): void
    {
        $this->layout = $layout;
    }

    /**
     * 关联数据
     * @param $relation
     * @return $this
     */
    public function setRelation($relation): self
    {
        $this->relation = $relation;
        return $this;
    }

    /**
     * 宽度
     * @param $width
     * @return $this
     */
    public function width($width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * 自定义节点数据
     * @param $node
     * @return $this
     */
    public function node($node): self
    {
        $this->node = $node;
        return $this;
    }

    /**
     * 对齐
     * @param string $align
     * @return $this
     * @throws Exception
     */
    public function align(string $align): self
    {
        $this->align = $align;
        return $this;
    }

    /**
     * 固定列
     * @param string $fixed
     * @return $this
     */
    public function fixed(string $fixed = 'right'): self
    {
        $this->fixed = $fixed;
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
     * @param string $name
     * @param        $value
     * @return $this
     */
    public function attr(string $name, $value): self
    {
        $this->attr[$name] = $value;
        return $this;
    }

    /**
     * 设置颜色
     * @param string $name
     * @return $this
     */
    public function color(string $name): self
    {
        $this->class[] = 'text-' . $name;
        return $this;
    }

    /**
     * 添加链接
     * @param string $name
     * @param string $route
     * @param array  $params
     * @param bool   $absolute
     * @return Link
     */
    public function link(string $name, string $route, array $params = [], bool $absolute = false): Link
    {
        if (!$this->element) {
            $this->element = new Table\Column\Link();
            $this->element->fields($this->layout->fields);
        }
        return $this->element->add($name, $route, $params, $absolute);
    }

    /**
     * 添加菜单
     * @param string $name
     * @param string $route
     * @param array  $params
     * @return Link
     */
    public function menu(string $name, string $route, array $params = []): Link
    {
        if (!$this->element) {
            $this->element = new Table\Column\Menu();
        }
        return $this->element->add($name, $route, $params);
    }

    /**
     * 副标题
     * @param string        $label
     * @param callable|null $callback
     * @return $this
     */
    public function desc(string $label, callable $callback = null): self
    {
        if (!$this->element && !$this->element instanceof Table\Column\RichText) {
            $this->element = new Table\Column\RichText();
            $this->element->setRelation($this->relation);
        }
        $this->element->desc($label, $callback);
        return $this;
    }

    /**
     * 图片显示
     * @param string        $label
     * @param callable|null $callback
     * @param int           $width
     * @param int           $height
     * @param string        $placeholder
     * @return $this
     */
    public function image(string $label, callable $callback = null, int $width = 10, int $height = 10, string $placeholder = ''): self
    {
        if (!$this->element && !$this->element instanceof Table\Column\RichText) {
            $this->element = new Table\Column\RichText();
        }
        $this->element->image($label, $width, $height, $placeholder, $callback);
        return $this;
    }

    /**
     * 格式化时间
     * @param $format
     * @return $this
     */
    public function date($format): self
    {
        $this->function[] = [
            'fun' => 'date',
            'params' => $format
        ];
        return $this;
    }

    /**
     * 显示隐藏
     * @param callable $callback
     * @return $this
     */
    public function show(callable $callback): self
    {
        $this->show = $callback;
        return $this;
    }

    /**
     * 列排序
     * @param int $num
     * @return $this
     */
    public function sort(int $num): self
    {
        $this->sort = $num;
        return $this;
    }

    /**
     * 排序条件
     */
    public function sorter($sorter = true): self
    {
        $this->sorter = $sorter;
        return $this;
    }

    /**
     * 列合并
     * @param int $num
     * @return $this
     */
    public function colspan(int $num): self
    {
        $this->colspan = $num;
        return $this;
    }

    // 分组表格
    public function children(string $name = '', string $label = '', $callback = null): self
    {
        $this->children[] = new Column($name, $label, $callback);
        return $this;
    }

    /**
     * 获取字段名
     * @return string
     */
    public function getLabel(): string
    {
        return Tools::converLabel($this->label, $this->relation);
    }

    /**
     * 获取列配置
     */
    public function getRender(): array
    {
        $render = $this->node;
        if ($this->node instanceof \Closure) {
            $render = call_user_func($this->node);
        }
        if ($this->element) {
            $render = $this->element->render($this->getLabel());
        }

        $node = [
            'title' => $this->name,
            'dataIndex' => $this->getLabel(),
            'width' => $this->width,
            'className' => implode(' ', $this->class),
            'colSpan' => $this->colspan,
            'sort' => $this->sort,
            'align' => $this->align,
        ];

        if ($this->fixed) {
            $node['fixed'] = $this->fixed;
        }

        if ($this->children) {
            $children = [];
            foreach ($this->children as $item) {
                $children[] = $item->getRender();
            }
            $node['children'] = $children;
        }

        if ($this->sorter) {
            $node['vBind:sortable'] = 'colSortable';
        }

        if ($render) {
            $node['render:rowData, rowIndex'] = $render;
        }

        return array_merge($node, $this->attr);
    }

    /**
     * 行数据
     * @param $rowData
     * @return array
     */
    public function getData($rowData): array
    {
        if ($this->relation) {
            // 解析关联数组
            $parsingData = Tools::parsingObjData($rowData, $this->relation, $this->label);
        } else {
            // 解析普通数组
            $parsingData = Tools::parsingArrData($rowData, $this->label);
        }

        // 回调处理
        if ($this->callback instanceof \Closure) {
            $callback = call_user_func($this->callback, $parsingData, $rowData);
            if ($callback) {
                $parsingData = $callback;
            }
        } else {
            $parsingData = $this->callback ?: $parsingData;
        }

        // 函数处理
        if ($this->function) {
            foreach ($this->function as $vo) {
                if (function_exists($vo['fun'])) {
                    $parsingData = call_user_func($vo['fun'], $vo['params'], $parsingData);
                }
            }
        }

        if ($this->label) {
            $data = [
                $this->getLabel() => $parsingData
            ];
        } else {
            $data = [];
        }

        // 元素数据
        if ($this->element && method_exists($this->element, 'getData')) {
            $data = array_merge($data, $this->element->getData($rowData, $this->getLabel(), $parsingData));
        }

        if ($this->children) {
            foreach ($this->children as $item) {
                $data = array_merge($data, $item->getData($rowData));
            }
        }

        return $data;
    }

    /**
     * 列条件
     * @param $query
     * @return false|void
     */
    public function execute($query)
    {
        $sort = request()->get('_sort');
        $value = $sort && $sort[$this->label] ? $sort[$this->label] : null;
        if (!$this->sorter || $value === null) {
            return false;
        }
        if ($this->sorter instanceof \Closure) {
            call_user_func($this->sorter, $query, $value);
        } else if ($this->sorter !== false) {
            $query->orderBy(is_string($this->sorter) ? $this->sorter : $this->label, $value === 'desc' ? 'desc' : 'asc');
        }
    }

    /**
     * 执行元素处理
     * @param callable $callback
     * @return $this
     */
    public function element(callable $callback){
        $callback($this->element);
        return $this;
    }

    /**
     * @param $method
     * @param $arguments
     * @return $this
     * @throws Exception
     */
    public function __call($method, $arguments)
    {
        $class = '\\Duxravel\\Core\\UI\\Table\\Column\\' . ucfirst($method);
        if (!class_exists($class)) {
            if (!$this->extend[$method]) {
                throw new \Exception('There is no form method "' . $method . '"');
            } else {
                $class = $this->extend[$method];
            }
        }
        $object = new $class(...$arguments);
        if (method_exists($object, 'fields')) {
            $object->fields($this->layout->fields);
        }
        $this->element = $object;
        return $this;
    }

}
