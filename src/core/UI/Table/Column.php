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
 * @method static Column\Progress progress(string $color = 'blue', int $max = 100)
 * @method static Column\Status status(array $map, array $color, string $type = 'badge')
 * @method static Column\Chart chart(int $day = 7, string $has = 'viewsData', string $key = 'pv', string $name = '访问量', string $type = 'area')
 * @method static Column\Tags tags(array $map, array $color)
 * @method static Column\Toggle toggle(string $field = '', string $url = '', array $params = [])
 * @method static Column\Input input(string $field = '', $url = '', array $params = [])
 * @package Duxravel\Core\UI\Table
 */
class Column
{

    protected string $name;
    protected string $label = '';
    protected ?\Closure $callback = null;
    protected array $attr = [];
    protected array $class = [];
    protected array $style = [];
    protected array $function = [];
    protected array $headerAttr = [];
    protected array $headerStyle = [];
    protected array $headerClass = [];
    protected ?int $colspan = null;
    protected ?\Closure $show = null;
    protected ?int $sort = null;
    protected $layout;
    protected $relation;
    protected $model;
    protected $element;
    protected $extend;

    /**
     * Column constructor.
     * @param string $name
     * @param string $label
     * @param null $callback
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
        $this->model = $layout->model();
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

        $width = strpos($width, '%') === false ? $width . 'px' : $width;
        $this->headerStyle['width'] = $width;
        $this->headerStyle['min-width'] = $width;
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
        if (!in_array($align, ['left', 'center', 'right'])) {
            throw new Exception('Align attribute does not exist');
        }
        $this->headerClass[] = 'text-' . $align;
        $this->class[] = 'text-' . $align;
        return $this;
    }

    /**
     * 设置样式
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function style(string $name, string $value): self
    {
        $this->style[$name] = $value;
        return $this;
    }

    /**
     * 设置头样式
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function headerStyle(string $name, string $value): self
    {
        $this->headerStyle[$name] = $value;
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
     * @param string $value
     * @return $this
     */
    public function attr(string $name, string $value): self
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
     * @param array $params
     * @return mixed
     */
    public function link(string $name, string $route = '', array $params = []): Link
    {
        if (!$this->element && !$this->element instanceof Table\Column\RichText) {
            $this->element = new Table\Column\Link();
        }
        return $this->element->add($name, $route, $params);
    }

    /**
     * 副标题
     * @param string $label
     * @param callable|null $callback
     * @return $this
     */
    public function desc(string $label, callable $callback = null): self
    {

        if (!$this->element && !$this->element instanceof Table\Column\RichText) {
            $this->element = new Table\Column\RichText();
        }
        $this->element->desc($label, $callback);
        return $this;
    }

    /**
     * 图片显示
     * @param string $label
     * @param callable|null $callback
     * @param int $width
     * @param int $height
     * @param string $placeholder
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
     * 获取列名
     * @return array
     */
    public function getHeader(): array
    {
        if ($this->show && !call_user_func($this->show)) {
            return [];
        }
        return [
            'name' => $this->name,
            'sort' => $this->sort,
            'attr' => Tools::toAttr($this->headerAttr),
            'class' => Tools::toClass($this->headerClass, true),
            'style' => Tools::toStyle($this->headerStyle, true)
        ];
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

    /**
     * 获取数据
     * @param $rowData
     * @return array
     */
    public function render($rowData): array
    {
        if ($this->show && !call_user_func($this->show)) {
            return [];
        }
        //公共组件
        if ($this->relation) {
            // 解析关联数组
            $parsingData = Tools::parsingObjData($rowData, $this->relation, $this->label);
        } else {
            // 解析普通数组
            $parsingData = Tools::parsingArrData($rowData, $this->label);
        }

        // 原始数据
        if ($parsingData instanceof Collection) {
            $originalData = '';
        } else {
            $originalData = $parsingData;
        }

        // 回调处理
        if ($this->callback instanceof \Closure) {
            $parsingData = call_user_func($this->callback, $parsingData, $rowData);
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

        // 扩展组件
        if ($this->element) {
            $data = $this->element->render($parsingData, $rowData);
        } else {
            $data = $parsingData;
        }

        return [
            'data' => $data,
            'original' => $originalData,
            'name' => $this->label,
            'colspan' => $this->colspan,
            'sort' => $this->sort,
            'class' => Tools::toClass($this->class, true),
            'attr' => Tools::toAttr($this->attr),
            'style' => Tools::toStyle($this->style, true)
        ];
    }

    /**
     * 回调类库
     * @param $method
     * @param $arguments
     * @return mixed
     * @throws \Exception
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
        $this->element = $object;
        return $this;
    }

}
