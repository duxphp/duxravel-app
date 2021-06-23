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


    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        $value = $this->getValue($value);
        $url = $value ?: route('service.image.placeholder', ['w' => 180, 'h' => 180, 't' => $this->attr['placeholder'] ?: '选择图片']);

        $this->class('relative h-32 lg:w-32 lg:h-32 border-2 border-gray-400 border-dashed rounded bg-cover bg-center bg-no-repeat block hover:border-blue-900');
        $this->style('background-image', "url('$url')");
        $this->style('background-size', "90% 90%");
        $imageData = array_merge($this->thumb, $this->water);
        if ($imageData) {
            $this->attr('data-image', $imageData);
        }
        return <<<HTML
            <div  data-js="form-image" {$this->toElement()}>
                    <div class="opacity-0 hover:opacity-100 absolute flex flex-col gap-2 justify-center w-full h-full bg-blue-200 bg-opacity-90 rounded cursor-pointer">
                        <div>
                            <div class="btn-blue mx-4 py-2 text-xs">
                                上传
                            </div>
                            <input name="{$this->field}" type="hidden" value="{$value}">
                        </div>
                        <div>
                            <div class="btn mx-4 py-2 text-xs" data-url>
                                地址
                            </div>
                        </div>
                    </div>

            </div>
        HTML;

    }

}
