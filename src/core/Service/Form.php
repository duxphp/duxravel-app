<?php

namespace Duxravel\Core\Service;

use Duxravel\Core\Model\FormData;

/**
 * 表单服务
 */
class Form
{

    /**
     * 获取表单UI
     * @param $formId
     * @param $form
     * @param int $id
     * @param string $hasType
     * @return mixed
     */
    public static function getFormUI($formId, $form, $id = 0, $hasType = '')
    {
        $model = new \Duxravel\Core\Model\FormData();
        $info = [];
        if ($id) {
            if ($hasType) {
                $info = $model->where('has_id', $id)->where('has_type', $hasType)->first();
            } else {
                $info = $model->where('data_id', $id)->first();
            }
            $info = $info->data;
        }
        $formInfo = \Duxravel\Core\Model\Form::find($formId);
        $formData = $formInfo->data;
        $formUI = self::formUI();
        foreach ($formData as $key => $vo) {
            if ($formUI[$vo['type']]) {
                call_user_func($formUI[$vo['type']]['ui'], $vo, $form, $info[$vo['field']]);
            }
        }
        return $form;
    }

    /**
     * 保存表单
     * @param int $formId
     * @param array|object $data
     * @param int $id 关联id | 数据id
     * @param string $hasType 表单类型
     * @return bool
     */
    public static function saveForm(int $formId, $data, int $id = 0, string $hasType = ''): bool
    {
        $formInfo = \Duxravel\Core\Model\Form::find($formId);
        $formData = $formInfo->data;

        $formUI = self::formUI();
        $tmpArr = [];
        foreach ($formData as $vo) {
            $tmpArr[$vo['field']] = $data[$vo['field']];
            if ($formUI[$vo['type']]['verify']) {
                call_user_func($formUI[$vo['type']]['verify'], $vo, $data[$vo['field']]);
            }
        }

        $model = new \Duxravel\Core\Model\FormData();
        if ($id) {
            if ($hasType) {
                $info = $model->where('form_id', $formId)->where('has_id', $id)->where('has_type', $hasType)->first();
            } else {
                $info = $model->where('form_id', $formId)->where('data_id', $id)->first();
            }
            if ($info) {
                $model = $info;
            }
        }

        if ($formInfo['manage'] && $formInfo['submit'] && $formInfo['audit']) {
            $model->status = 0;
        } else {
            $model->status = 1;
        }

        if ($hasType) {
            $model->has_type = $hasType;
            $model->has_id = $id;
        } else {
            $model->has_type = FormData::class;
        }
        $model->data = $tmpArr;
        $model->form_id = $formId;
        $model->save();
        return true;
    }

    /**
     * 删除关联
     * @param $id
     * @param string $hasType
     * @return bool
     * @throws \Exception
     */
    public static function delForm($id, string $hasType = ''): bool
    {
        $model = new \Duxravel\Core\Model\FormData();
        if ($hasType) {
            $model->where('has_id', $id)->where('has_type', $hasType)->delete();
        } else {
            $model->where('data_id', $id)->delete();
        }
        return true;
    }


    /**
     * 设置UI
     * @return array
     */
    protected static function formUI(): array
    {
        return [
            'text' => [
                'ui' => function ($config, $form, $value) {
                    if ($config['data']['type'] === 'text') {
                        $el = $form->text($config['name'], $config['field']);
                    }
                    if ($config['data']['type'] === 'number') {
                        $el = $form->text($config['name'], $config['field'])->type('number');
                    }
                    if ($config['data']['type'] === 'email') {
                        $el = $form->email($config['name'], $config['field']);
                    }
                    if ($config['data']['type'] === 'tel') {
                        $el = $form->tel($config['name'], $config['field']);
                    }
                    if ($config['data']['type'] === 'password') {
                        $el = $form->password($config['name'], $config['field']);
                    }
                    if ($config['data']['type'] === 'ip') {
                        $el = $form->text($config['name'], $config['field'])->type('ip');
                    }
                    if ($config['data']['type'] === 'url') {
                        $el = $form->text($config['name'], $config['field'])->type('url');
                    }
                    if ($config['data']['type'] === 'date') {
                        $el = $form->text($config['name'], $config['field'])->type('date');
                    }
                    if ($config['data']['type'] === 'time') {
                        $el = $form->text($config['name'], $config['field'])->type('time');
                    }
                    $el->value($value);
                },
                'verify' => function ($config, $value) {
                    if ($config['data']['required']) {
                        if (!$value) {
                            app_error('请填写' . $config['name']);
                        }
                    }
                }
            ],
            'select' => [
                'ui' => function ($config, $form, $value) {
                    $form->select($config['name'], $config['field'], function () use ($config) {
                        $option = array_filter(explode("\n", $config['data']['options']));
                        $tmpArr = [];
                        foreach ($option as $vo) {
                            $tmpArr[$vo] = $vo;
                        }
                        return $tmpArr;
                    })->value($value);
                }
            ],
            'radio' => [
                'ui' => function ($config, $form, $value) {
                    $form->radio($config['name'], $config['field'], function () use ($config) {
                        $option = array_filter(explode("\n", $config['data']['options']));
                        $tmpArr = [];
                        foreach ($option as $vo) {
                            $tmpArr[$vo] = $vo;
                        }
                        return $tmpArr;
                    })->value($value);
                }
            ],
            'checkbox' => [
                'ui' => function ($config, $form, $value) {
                    $form->checkbox($config['name'], $config['field'], function () use ($config) {
                        $option = array_filter(explode("\n", $config['data']['options']));
                        $tmpArr = [];
                        foreach ($option as $vo) {
                            $tmpArr[$vo] = $vo;
                        }
                        return $tmpArr;
                    })->value($value);
                }
            ],
            'image' => [
                'ui' => function ($config, $form, $value) {
                    $form->image($config['name'], $config['field'])->attr('data-mode', $config['data']['type'] ? 'upload' : 'manage')->value($value);
                },
                'verify' => function ($config, $value) {
                    if ($config['data']['required']) {
                        if (!$value) {
                            app_error('请上传' . $config['name']);
                        }
                    }
                }
            ],
            'images' => [
                'ui' => function ($config, $form, $value) {
                    $form->images($config['name'], $config['field'])->attr('data-mode', $config['data']['type'] ? 'upload' : 'manage')->value($value);
                },
                'verify' => function ($config, $value) {
                    if ($config['data']['required']) {
                        if (!$value) {
                            app_error('请填写' . $config['name']);
                        }
                    }
                    if ($config['data']['num']) {
                        if (count($value) > $config['data']['num']) {
                            app_error('上传' . $config['name'] . '超过' . $config['data']['num'] . '张');
                        }
                    }
                }
            ],
            'file' => [
                'ui' => function ($config, $form, $value) {
                    $form->file($config['name'], $config['field'])->attr('data-mode', $config['data']['type'] ? 'upload' : 'manage')->value($value);
                },
                'verify' => function ($config, $value) {
                    if ($config['data']['required']) {
                        if (!$value) {
                            app_error('请上传' . $config['name']);
                        }
                    }
                }
            ],
            'date' => [
                'ui' => function ($config, $form, $value) {
                    if ($config['data']['type'] === 'date') {
                        $form->date($config['name'], $config['field'])->value($value);
                    }
                    if ($config['data']['type'] === 'time') {
                        $form->time($config['name'], $config['field'])->value($value);
                    }
                    if ($config['data']['type'] === 'datetime') {
                        $form->datetime($config['name'], $config['field'])->value($value);
                    }
                    if ($config['data']['type'] === 'range') {
                        $form->daterange($config['name'], $config['field'])->value($value);
                    }
                },
                'verify' => function ($config, $value) {
                    if ($config['data']['required']) {
                        if (!$value) {
                            app_error('请选择' . $config['name']);
                        }
                    }
                }
            ],
            'editor' => [
                'ui' => function ($config, $form, $value) {
                    $form->editor($config['name'], $config['field'])->value($value);
                },
                'verify' => function ($config, $value) {
                    if ($config['data']['required']) {
                        if (!$value) {
                            app_error('请输入' . $config['name']);
                        }
                    }
                }
            ],
            'color' => [
                'ui' => function ($config, $form, $value) {
                    if ($config['data']['type'] === 'color') {
                        $form->color($config['name'], $config['field'])->value($value);
                    }
                    if ($config['data']['type'] === 'picker') {
                        $form->color($config['name'], $config['field'])->value($value)->picker();
                    }
                }
            ],
        ];
    }
}

