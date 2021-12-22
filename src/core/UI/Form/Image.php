<?php

namespace Duxravel\Core\UI\Form;

use Illuminate\Support\Collection;
use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

/**
 * Class Image
 * 图片上传
 * @package Duxravel\Core\UI\Form
 */
class Image extends Element implements Component
{

    private array $thumb = [];
    private array $water = [];
    protected string $type = 'manage';
    protected string $url = '';

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
     * 缩图
     * @param int $width
     * @param int $height
     * @param string $type
     * @return $this
     */
    public function thumb(int $width, int $height, string $type = 'scale'): self
    {
        $this->thumb = [
            'width' => $width,
            'height' => $height,
            'thumb' => $type
        ];
        return $this;
    }

    /**
     * 水印
     * @param string $position
     * @param int $alpha
     * @return $this
     */
    public function water(string $position = 'center', int $alpha = 80): self
    {
        $this->water = [
            'alpha' => $alpha,
            'water' => $position
        ];
        return $this;
    }

    public function type($type = 'manage')
    {
        $this->type = $type;
        return $this;
    }


    /**
     * 渲染组件
     * @return string
     */
    public function render()
    {
        $data = [
            'nodeName' => 'app-file',
            'format' => 'image',
            'image' => true,
            'size' => 125
        ];
        if ($this->url) {
            $data['upload'] = $this->url;
        }
        if ($this->type) {
            $data['type'] = $this->type;
        }
        if ($this->model) {
            $data['vModel:value'] = $this->getModelField();
        }
        return $data;
    }

}
