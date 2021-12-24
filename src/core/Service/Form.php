<?php

namespace Duxravel\Core\Service;

/**
 * 表单相关
 */
class Form
{

    public static function form($id)
    {
        $formInfo = \Duxravel\Core\Model\Form::find($id);
        if (!$formInfo || $formInfo->manage) {
            app_error('表单不存在', 404);
        }
        return $formInfo;
    }

    public static function info($id)
    {
        $info = \Duxravel\Core\Model\FormData::find($id);
        if (!$info) {
            app_error('信息不存在', 404);
        }
        $formInfo = \Duxravel\Core\Model\Form::find($info->form_id);
        if (!$formInfo || $formInfo->manage) {
            app_error('表单不存在', 404);
        }
        return [$info, $formInfo];
    }

    public static function push($id, $captchaKey = '')
    {
        $formInfo = \Duxravel\Core\Model\Form::find($id);
        if (!$formInfo || $formInfo->manage || !$formInfo->submit) {
            app_error('表单不存在', 404);
        }
        $rules = [
            'captcha' => $captchaKey ? 'required|captcha_api:'. $captchaKey . ',math' : 'required|captcha'
        ];
        $validator = validator()->make(request()->input(), $rules);
        if ($validator->fails()) {
            app_error('验证码输入有误');
        }

        $lastInfo = \Duxravel\Core\Model\FormData::latest()->first();

        if ($lastInfo->created_at->lt($formInfo['Interval'])) {
            app_error('提交太快了，请稍等');
        }

        $input = request()->input();
        $formData = $formInfo->data;
        $uploadFields = [];
        foreach ($formData as $vo) {
            if ($vo['type'] === 'image' || $vo['type'] === 'file' || $vo['type'] === 'images') {
                $uploadFields[] = $vo['field'];
            }
        }
        if ($uploadFields) {
            $files = request()->allFiles();
            $filetKeys = array_keys($files);
            foreach ($filetKeys as $key) {
                if (!in_array($key, $uploadFields)) {
                    app_error('非法文件上传');
                }
            }
            $files = \Duxravel\Core\Util\Upload::load('web');
            $fileData = [];
            foreach ($files as $file) {
                $fileData[$file['field']][] = $file;
            }
            foreach ($formData as $vo) {
                if ($vo['type'] === 'image' || $vo['type'] === 'file') {
                    $input[$vo['field']] = $fileData[$vo['field']][0]['url'];
                }
                if ($vo['type'] === 'images') {
                    $input[$vo['field']] = array_column($fileData[$vo['field']], 'url');
                }
            }
        }

        \Duxravel\Core\Util\Form::saveForm($id, $input);
        return $formInfo;
    }
}

