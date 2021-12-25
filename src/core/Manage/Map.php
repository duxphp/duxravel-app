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
        try {
            $object = new WebService();
            $ip = $object->getArea($ip);
        } catch (\Exception $e) {
            $ip = [];
        }
        return app_success('ok', $ip);
    }

    public function weather()
    {
        $ip = request()->get('ip') ?: null;
        $city = request()->get('city') ?: null;

        try {
            $object = new WebService();
            $data = $object->getWeather($city, $ip);
        } catch (\Exception $e) {
            $data = [];
        }
        return app_success('ok', $data);
    }
}
