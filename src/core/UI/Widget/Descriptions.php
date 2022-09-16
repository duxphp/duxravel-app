<?php

namespace Duxravel\Core\UI\Widget;

/**
 * 描述列表 Descriptions
 *
 * @see     [arco.design] https://arco.design/vue/component/descriptions
 * @package Duxravel\Core\UI\Widget
 */
class Descriptions extends Widget
{

    /**
     * @var string 标题（可选）
     */
    private string $title = '';

    /**
     * @var string 每行放置到数据个数（可选）
     *             格式为Number: 3
     *             格式为Grid: {xs:1, md:3, lg:4}
     */
    private string $column = '';

    /**
     * @var string 描述列表的排列方式（可选）
     *             支持的值: 'horizontal' | 'vertical' | 'inline-horizontal' | 'inline-vertical'
     */
    private string $layout = '';

    /**
     * @var string 标签或文字的对齐位置（可选）
     *             支持的对齐方式: left, center, right
     *             格式①: 仅设置文字有效, "right"
     *             格式②: 标签和文字都可以, "{label: 'left', value: 'right'}"
     */
    private string $align = '';

    /**
     * @var string 描述列表的大小（可选）
     *             支持的值: 'mini' | 'small' | 'medium' | 'large'
     */
    private string $size = '';

    /**
     * @var bool 是否显示边框（可选）
     */
    private ?bool $bordered = false;

    /**
     * @var array 数据, 一项包含label和value两个字段
     *            格式: [{"label":"Name","value":"Socrates"}]
     */
    private array $data = [];

    /**
     * @param  array         $data
     * @param  callable|null $callback
     */
    public function __construct(array $data = [], callable $callback = null)
    {
        $this->add(...$data);
        $this->callback = $callback;
    }

    /**
     * 添加数据项
     *
     * @param  array ...$data 一条或多条数据
     * @return $this
     */
    public function add(array ...$data)
    {
        foreach ($data as $item) {
            if (isset($item['label']) && isset($item['value'])) {
                $this->data[] = $item;
            }
        }
        return $this;
    }

    /**
     * @param  string $title
     * @return $this
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param  string $column
     * @return $this
     */
    public function setColumn(string $column)
    {
        $this->column = $column;
        return $this;
    }

    /**
     * @param  string $layout
     * @return $this
     */
    public function setLayout(string $layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * @param  string $align
     * @return $this
     */
    public function setAlign(string $align)
    {
        $this->align = $align;
        return $this;
    }

    /**
     * @param  string $size
     * @return $this
     */
    public function setSize(string $size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @param  bool $bordered
     * @return $this
     */
    public function setBordered(?bool $bordered)
    {
        $this->bordered = $bordered;
        return $this;
    }

    public function render(): array
    {
        if (empty($this->data)) {
            return [
                'nodeName' => 'a-empty',
            ];
        }

        $node = [
            'nodeName' => 'a-descriptions',
            'data'     => $this->data,
        ];
        $this->title && $node['title'] = $this->title;
        $this->column && $node['column'] = $this->column;
        $this->layout && $node['layout'] = $this->layout;
        $this->size && $node['size'] = $this->size;
        is_bool($this->bordered) && $node['bordered'] = $this->bordered;

        if ($this->align) {
            if (in_array($this->align, ['left', 'center', 'right'])) {
                $node['align'] = $this->align;
            } else {
                $node['vBind:align'] = $this->align;
            }
        }

        return $node;
    }

}
