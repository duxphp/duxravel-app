<?php

namespace Duxravel\Core\Service;

/**
 * Class Type
 * @package Duxravel\Core\Service
 */
class Type
{
    /**
     * 获取终端类型
     * @return array
     */
    public function getDriverType(): array
    {
        return [
            'h5' => 'H5',
            'wechat' => '微信公众号',
            'weapp' => '微信小程序',
            'app' => 'APP',
            'web' => '电脑'
        ];
    }

}
