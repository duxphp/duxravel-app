<?php

namespace Duxravel\Core\Traits;

/**
 * Class Form
 * @package Duxravel\Core\Model
 */
trait Form
{
    public function form(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(\Duxravel\Core\Model\FormData::class, 'has', 'has_type');
    }

    /**
     * 保存表单
     * @param $formId
     * @param $data
     * @return bool
     */
    public function formSave($formId, $data): bool
    {
        $id = $this->{$this->primaryKey};
        if (!$id || !$formId) {
            return false;
        }
        return \Duxravel\Core\Service\Form::saveForm($formId, $data, $id, get_called_class());
    }

    /**
     * 删除表单
     * @return bool
     */
    public function formDel(): bool
    {
        $id = $this->{$this->primaryKey};
        if (!$id) {
            return false;
        }
        return $this->form()->delete();
    }

}
