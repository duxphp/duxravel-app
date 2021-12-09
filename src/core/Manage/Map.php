<?php

namespace Duxravel\Core\Manage;

use Duxravel\Core\Util\WebService;
use Illuminate\Http\Request;

/**
 * 用户登录
 * @package Modules\System\System
 */
trait Map
{

    public function area()
    {
        $ip = request()->get('ip') ?: null;
        $object = new WebService();
        return app_success('ok', $object->getArea($ip));
    }

    public function weather()
    {
        $ip = request()->get('ip') ?: null;
        $city = request()->get('city') ?: null;
        $object = new WebService();
        return app_success('ok', $object->getWeather($city, $ip));
    }
}
