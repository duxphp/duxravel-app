<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Datetime
 * 日期时间选择器
 * @package Duxravel\Core\UI\Form
 */
class Datetime extends Element implements Component
{

    protected Text $object;

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
        $this->object = new Text($this->name, $this->field, $this->has);
        $this->object->attr('data-js', 'form-date');
        $this->object->attr('data-type', 'datetime');
        $this->object->type('text');
        $this->object->attr('placeholder', '请选择'.$this->name);
        $this->object->afterIcon('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /><line x1="11" y1="15" x2="12" y2="15" /><line x1="12" y1="15" x2="12" y2="18" /></svg>');
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        $value = $this->getValue($value ?: null);
        return $this->object->render($value ? date('Y-m-d H:i:s', $value) : '');
    }

    /**
     * 获取输入值
     * @param $data
     * @return string|null
     */
    public function getInputData($data): ?string
    {
        return $data ? strtotime($data) : null;
    }

}
