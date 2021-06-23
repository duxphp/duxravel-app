<?php

namespace Duxravel\Core\UI\Form;

use Illuminate\Support\Collection;
use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

/**
 * Class Images
 * 组图上传
 * @package Duxravel\Core\UI\Form
 */
class Images extends Element implements Component
{
    /**
     * Text constructor.
     * @param  string  $name
     * @param  string  $field
     * @param  string  $has
     */
    public function __construct(string $name, string $field, string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->has = $has;
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        $values = $this->getValueArray($value) ?: [];

        $inner = [];
        foreach ($values as $vo) {
            $inner = <<<HTML
                <div class="relative w-32 h-32 border-2 border-gray-400 border-dashed rounded bg-cover bg-center bg-no-repeat block hover:border-blue-900" style="background-size:90%; background-image:url('$vo')" data-item>
                    <div class="opacity-0 hover:opacity-100 absolute flex items-center justify-center w-full h-full bg-blue-200 bg-opacity-90 rounded cursor-pointer"><button type="button" class="btn-red" data-del>删除</button></div>
                    <input type="hidden" name="$this->field[]" value="$vo">
                </div>
            HTML;
        }
        $innerHtml = implode('', $inner);

        return <<<HTML
            <div {$this->toElement()} data-js="form-images" data-name="$this->field">
                $innerHtml
                <div class="relative w-32 h-32 border-2 border-gray-400 border-dashed rounded bg-cover bg-center bg-no-repeat block hover:border-blue-900" data-plus>
                    <div class="text-gray-500 hover:text-blue-900 absolute flex items-center justify-center w-full h-full bg-gray-100 bg-opacity-90 rounded cursor-pointer">
                        <div class=" w-6 h-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        HTML;
    }

}
