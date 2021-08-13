<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Widget\Icon;

/**
 * Class Data
 * 表格数据编辑器
 * @package Duxravel\Core\UI\Form
 */
class Data extends Element implements Component
{
    protected array $column = [];
    protected bool $option = true;
    protected ?int $number = null;

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
    public function num(int $num = 0): self
    {
        $this->number = $num;
        return $this;
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value)
    {
        $url = route('service.image.placeholder', ['w' => 64, 'h' => 64, 't' => $this->attr['placeholder'] ?: '图片']);

        $inner = [];
        $default = [];
        foreach ($this->column as $column) {
            $default[$column['key']] = '';
            $field = "value['{$column['key']}']";
            if ($column['type'] === 'text') {
                $inner[] = [
                    'nodeName' => 'div',
                    'class' => 'flex-grow',
                    'child' => [
                        'nodeName' => 'n-input',
                        'placeholder' => '请输入' . $column['name'],
                        'vModel:value' => $field
                    ]
                ];
            }
            if ($column['type'] === 'image') {
                $inner[] = [
                    'nodeName' => 'div',
                    'class' => 'flex-none',
                    'child' => [
                        'nodeName' => 'n-upload',
                        'action' => $this->uploadUrl,
                        'class' => 'relative w-9 h-9 border border-gray-400 border-dashed rounded bg-cover bg-center bg-no-repeat block hover:border-blue-900',
                        'vBind:style' => "{'background-size': '90%', 'background-image': $field || 'url($url)'}",
                        'child' => [
                            'nodeName' => 'div',
                            'class' => 'opacity-0 hover:opacity-100 absolute inset-0 flex items-center justify-center w-full h-full bg-blue-200 bg-opacity-90 rounded cursor-pointer ',
                            'child' => (new Icon('plus'))->render(),
                        ]
                    ]
                ];
            }
            if ($column['type'] === 'show') {
                $inner[] = [
                    'nodeName' => 'div',
                    'class' => 'flex-grow',
                    'child' => "{{ $field || '-'}}"
                ];
            }
        }

        $create = json_encode($default);
        $data = [
            'nodeName' => 'n-dynamic-input',
            'vModel:value' => $this->getModelField(),
            'vBind:on-create' => "() => { return $create }",
            'child' => [
                'vSlot' => '{ index, value }',
                'nodeName' => 'div',
                'class' => 'flex flex-grow gap-4 items-center',
                'child' => $inner
            ]
        ];

        if ($this->number) {
            $data['max'] = $this->number;
        }

        if (!$this->option) {
            $data['min'] = $this->number;
            $data['max'] = $this->number;
        }

        return $data;
    }

    public function dataValue($value)
    {
        return $this->getValue($value) ?: [];
    }

}
