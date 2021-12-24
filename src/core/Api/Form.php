<?php

namespace Duxravel\Core\Api;

use Duxravel\Core\Api\Api;
use Modules\Article\Resource\TagsCollection;
use Duxravel\Core\Resource\FormDataCollection;
use Duxravel\Core\Resource\FormDataResource;
use Duxravel\Core\Resource\FormResource;

class Form extends Api
{

    public function list($id)
    {
        $formInfo = \Duxravel\Core\Service\Form::form($id);
        $data = new \Duxravel\Core\Model\FormData();
        $data = $data->where('status', 1)->where('form_id', $id);
        $res = new FormDataCollection($data->paginate());
        return $this->success($res);
    }

    public function info($id)
    {
        [$info, $formInfo] = \Duxravel\Core\Service\Form::info($id);
        return $this->success(new FormDataResource($info));
    }

    public function push($id)
    {
        $key = request('key');
        if (!$key) {
            return $this->error('缺少验证码参数');
        }
        $formInfo = \Duxravel\Core\Service\Form::push($id, $key);
        return $this->success(new FormResource($formInfo));
    }

}
