<?php

namespace Duxravel\Core\Api;

use Duxravel\Core\Controllers\Controller;

class Api extends Controller
{

    public function success($data = null, $message = '', $code = 200, $headers = [], $option = 0)
    {
        return \Response::success($data, $message, $code, $headers, $option);
    }

    public function error($message = '', $code = 500, $errors = null, $header = [], $options = 0)
    {
        return \Response::fail($message, $code, $errors, $header, $options);
    }
}
