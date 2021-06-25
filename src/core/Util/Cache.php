<?php

namespace Duxravel\Core\Util;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * 系统缓存
 */
class Cache
{

    /**
     * 缓存文件
     * @param $rule
     * @return array
     */
    public static function globList($rule)
    {
        $list = [];
        foreach (glob($rule) as $file) {
            $list[] = $file;
        }
        return $list;
    }


}
