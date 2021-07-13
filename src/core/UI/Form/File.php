<?php

namespace Duxravel\Core\UI\Form;

use Illuminate\Support\Collection;

/**
 * Class File
 * @package Duxravel\Core\UI\Form
 */
class File extends Element implements Component
{
    protected string $type = 'upload';

    /**
     * File constructor.
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

    public function type($type = 'upload')
    {
        $this->type = $type;
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
        $text = $this->type === 'manage' ? '点击选择文件' : ($value ? '文件已上传，点击重新上传' : '点击上传文件');
        return <<<HTML
            <label data-js="form-file" data-mode="$this->type" data-loading=".progress" data-tip-el=".status" data-text-loading="文件上传中，请稍等" data-text-fail="文件上传失败" data-text-success="文件上传成功"
            class="select-none flex flex-col gap-2 justify-center items-center relative block cursor-pointer relative h-32 lg:h-32 border-2 border-gray-400 border-dashed rounded bg-cover bg-center bg-no-repeat block text-gray-500 hover:text-blue-900 hover:border-blue-900">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>

                    <input type="hidden" name="$this->field" value="$value">
                </div>
                <div class="text-gray-500"><span class="status">$text</span> <span class="progress text-blue-900"></span></div>
            </label>
            HTML;
    }

}
