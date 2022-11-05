<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Widget\Icon;

/**
 * 动态数据编辑器
 * @package Duxravel\Core\UI\Form
 */
class Data extends Element implements Component
{
    protected array $column = [];
    protected array $package = [];
    protected bool $option = true;
    protected ?int $numberMax = null;
    protected ?int $numberMin = null;
    protected bool $wrap = false;

    /**
     * Text constructor.
     * @param string $name
     * @param string $field
     * @param string $has
     */
    public function __construct(string $name, string $field, string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->has = $has;
    }

    /**
     * 文本列
     * @param string $name
     * @param string $field
     * @param null $width
     * @return $this
     */
    public function text(string $name, string $field, $width = null): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'text',
            'width' => $width,
        ];
        return $this;
    }

    /**
     * 选择框
     * @param string $name
     * @param string $field
     * @param null $data
     * @param null $width
     * @return $this
     */
    public function select(string $name, string $field, $data = null, $width = null): self
    {
        $this->column[] = [
            'name'  => $name,
            'key'   => $field,
            'type'  => 'select',
            'data'  => $data,
            'width' => $width,
        ];
        return $this;
    }

    /**
     * 图片列
     * @param string $name
     * @param string $field
     * @param null $width
     * @return $this
     */
    public function image(string $name, string $field, $width = null): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'image',
            'width' => $width,
        ];
        return $this;
    }

    /**
     * 展示列
     * @param string $name
     * @param string $field
     * @param null $width
     * @return $this
     */
    public function show(string $name, string $field, $width = null): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'show',
            'width' => $width,
        ];
        return $this;
    }

    /**
     * Html内容
     * @param string|array $field
     * @param $callback
     * @return $this
     */
    public function html($field, $callback): self
    {
        $this->column[] = [
            'key'      => $field,
            'type'     => 'html',
            'callback' => $callback
        ];
        return $this;
    }

    /**
     * 自定义表单元素
     * @param Component $package
     * @return $this
     */
    public function form(Component $package ): self
    {
        $this->package[] = $package;
        return $this;
    }

    /**
     * 隐藏列
     * @param string $name
     * @param string $field
     * @return $this
     */
    public function hidden(string $name, string $field): self
    {
        $this->column[] = [
            'name' => $name,
            'key' => $field,
            'type' => 'hidden'
        ];
        return $this;
    }

    /**
     * 操作状态
     * @param bool $status
     * @return $this
     */
    public function option(bool $status = true): self
    {
        $this->option = $status;
        return $this;
    }

    /**
     * 最大数量
     * @param int $num
     * @return $this
     */
    public function max(int $num = 0): self
    {
        $this->numberMax = $num;
        return $this;
    }

    /**
     * 最小数量
     * @param int $num
     * @return $this
     */
    public function min(int $num = 0): self
    {
        $this->numberMin = $num;
        return $this;
    }

    /**
     * 自动换行
     * @param bool $wrap
     * @return $this
     */
    public function wrap(bool $wrap = true): self
    {
        $this->wrap = $wrap;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $inner = [];
        $default = [];
        foreach ($this->column as $column) {
            if ($column['type'] === 'html') {
                $keys = is_array($column['key']) ? $column['key'] : [$column['key']];
                foreach ($keys as $key => $vo) {
                    if (is_numeric($key)) {
                        $key = $vo;
                        $vo = '';
                    }
                    $default[$key] = $vo;
                }
                if ($column['callback'] instanceof \Closure) {
                    $inner[] = call_user_func($column['callback']);
                } else if (is_array($column['callback'])) {
                    $inner[] = $column['callback'];
                }
                continue;
            }

            $default[$column['key']] = '';
            $field = "value['{$column['key']}']";
            $innerNode = [];
            if ($column['type'] === 'text') {
                $innerNode = [
                    'nodeName' => 'div',
                    'class' => 'flex-grow',
                    'child' => [
                        'nodeName' => 'a-input',
                        'placeholder' => '请输入' . $column['name'],
                        'vModel:model-value' => $field
                    ]
                ];
            }
            if ($column['type'] === 'image') {
                $innerNode = [
                    'nodeName' => 'div',
                    'class' => 'flex-none',
                    'child' => [
                        'nodeName' => 'app-file',
                        'image' => 'true',
                        'mini' => true,
                        'size' => 8,
                        'vModel:value' => $field
                    ]
                ];
            }
            if ($column['type'] === 'show') {
                $innerNode = [
                    'nodeName' => 'div',
                    'class' => 'flex-grow',
                    'child' => "{{ $field || '-'}}"
                ];
            }
            if ($column['type'] === 'select') {
                $options = [];
                foreach ($column['data'] as $key => $vo) {
                    $options[] = [
                        'label' => $vo,
                        'value' => $key
                    ];
                }
                $innerNode = [
                    'nodeName' => 'div',
                    'class'    => 'flex-grow',
                    'child'    => [
                        'nodeName'     => 'app-select',
                        'nParams'      => [
                            'placeholder' => '请选择' . $column['name'],
                            'options'     => $options,
                            'allowSearch' => true
                        ],
                        'vModel:value' => $field
                    ]
                ];
            }

            if ($column['width']) {
                $innerNode['style'] = [
                    'width' => $column['width']
                ];
            }
            $inner[] = $innerNode;
        }

        foreach ($this->package as $vo) {
            $vo = $vo->model("value.");
            $default[$vo->getField()] = $vo->getValue();
            $inner[] = $vo->render();
        }

        $create = json_encode($default);
        $data = [
            'nodeName' => 'app-dynamic-data',
            'vModel:value' => $this->getModelField(),
            'vBind:on-create' => "() => { return $create }",
            'key' => $this->getModelField(),
            'renderRow: value, index' => [
                'nodeName' => 'div',
                'class' => 'flex flex-grow gap-4 items-center' . ($this->wrap ? ' flex-wrap' : ''),
                'child' => $inner
            ]
        ];

        if ($this->numberMax) {
            $data['max'] = $this->numberMax;
        }

        if ($this->numberMin) {
            $data['min'] = $this->numberMin;
        }

        return $data;
    }

    /**
     * @param $data
     * @return array|null
     */
    public function dataValue($data): ?array
    {
        $data = $this->getValue($data);
        return $data ?: [];
    }
}
