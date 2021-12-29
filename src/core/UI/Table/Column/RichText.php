<?php

namespace Duxravel\Core\UI\Table\Column;

use Duxravel\Core\UI\Tools;

/**
 * Class RichText
 */
class RichText implements Component
{
    private array $desc = [];
    private array $image = [];

    /**
     * @param string $label
     * @param callable|null $callback
     * @return $this
     */
    public function desc(string $label, ?callable $callback = null): self
    {
        $this->desc[] = ['label' => $label, 'callback' => $callback];
        return $this;
    }

    /**
     * @param string $label
     * @param int $width
     * @param int $height
     * @param string $placeholder
     * @param callable|null $callback
     * @return $this
     */
    public function image(string $label, int $width = 10, int $height = 10, string $placeholder = '', ?callable $callback = null): self
    {
        $this->image[] = [
            'label' => $label,
            'width' => $width,
            'height' => $height,
            'placeholder' => $placeholder,
            'callback' => $callback
        ];
        return $this;
    }

    /**
     * @param $rowData
     * @return array
     */
    public function getData($rowData): array
    {
        $data = [];
        foreach ($this->image as $key => $vo) {
            $url = Tools::parsingArrData($rowData, $vo['label'], true);
            if ($vo['callback'] instanceof \Closure) {
                $url = call_user_func($vo['callback'], $url, $rowData);
            }
            if (filter_var($url, FILTER_VALIDATE_URL) === false) {
                $url = route('service.image.placeholder', ['w' => 100, 'h' => 100, 't' => $vo['placeholder'] ?: '暂无']);
            }
            $data[$vo['label']] = $url;
        }
        foreach ($this->desc as $key => $vo) {
            $var = Tools::parsingArrData($rowData, $vo['label']);
            if ($vo['callback'] instanceof \Closure) {
                $var = call_user_func($vo['callback'], $var, $rowData);
            }
            $data[$vo['label']] = $var;
        }
        return $data;
    }

    /**
     * @param $label
     * @return array
     */
    public function render($label): array
    {

        $imageNode = [];
        if ($this->image) {
            foreach ($this->image as $vo) {
                $imageNode[] = [
                    'nodeName' => 'div',
                    'class' => "flex-none bg-cover  w-{$vo['width']} h-{$vo['height']}",
                    'vBind:style' => "{'background-image': 'url(' + rowData.record['{$vo['label']}'] + ')'}"
                ];
            }
        }

        $descNode = [];
        if ($this->desc) {
            foreach ($this->desc as $vo) {
                $descNode[] = [
                    'nodeName' => 'div',
                    'class' => "text-gray-500 overflow-ellipsis max-w-md",
                    'child' => "{{rowData.record['{$vo['label']}']}}"
                ];
            }
        }

        return [
            'nodeName' => 'div',
            'class' => 'flex items-center gap-2',
            'child' => [
                ...$imageNode,
                [
                    'nodeName' => 'div',
                    'class' => 'flex-grow',
                    'child' => [
                        [
                            'nodeName' => 'div',
                            'class' => 'overflow-ellipsis max-w-md',
                            'child' => '{{rowData.record["'.$label.'"]}}'
                        ],
                        ...$descNode
                    ]

                ]
            ]
        ];

    }

}
