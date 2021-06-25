<?php

namespace Duxravel\Core\Util;

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
        $cacheList = cache()->get('file-' . $rule);
        if ($cacheList) {
            return $cacheList;
        }
        $list = [];
        foreach (glob($rule) as $file) {
            $list[] = $file;
        }
        cache()->put('file-' . $rule, $list);
        return $list;
    }

}