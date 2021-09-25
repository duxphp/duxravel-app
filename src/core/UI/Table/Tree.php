<?php

namespace Duxravel\Core\UI\Table;

use Duxravel\Core\UI\Form\Select;
use Duxravel\Core\UI\Widget\Link;

/**
 * 树形列表
 * Class Column
 * @package Duxravel\Core\UI\Filter
 */
class Tree
{

    protected $label = '';
    protected array $node = [];
    protected array $prefix = [];
    protected array $suffix = [];
    protected $link;

    /**
     * @param $label
     */
    public function __construct($label, $node = [])
    {
        $this->label = $label;
        $this->node = $node;

    }

    /**
     * @param $node
     * @return $this
     */
    public function prefix($node): self
    {
        $this->prefix = $node;
        return $this;
    }

    /**
     * @param $node
     * @return $this
     */
    public function suffix($node): self
    {
        $this->suffix = $node;
        return $this;
    }

    /**
     * 添加链接
     * @param string $name
     * @param string $route
     * @param array $params
     * @return Link
     */
    public function link(string $name, string $route, array $params = []): Link
    {
        if (!$this->link) {
            $this->link = new Column\Link();
        }
        return $this->link->add($name, $route, $params);
    }


    /**
     * 渲染组件
     * @return array
     */
    public function render(): array
    {
        $suffix = $this->suffix;
        if ($this->link) {
            $suffix = $this->link->render('');
        }
        return [
            'node' => $this->node ?: ['nodeName' => 'div', 'child' => "{{rowData['$this->label']}}"],
            'prefix' => $this->prefix,
            'suffix' => $suffix
        ];
    }

    /**
     * 组件行数据
     * @param $rowData
     * @return array|\Closure[]
     */
    public function getData($rowData)
    {
        $data = [];
        // 元素数据
        if ($this->link) {
            $data = array_merge($data, $this->link->getData($rowData));
        }

        return $data;
    }

}
