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
        $cacheList = \Cache::tags(['duxravel-run'])->get('file-' . $rule);
        if ($cacheList) {
            return $cacheList;
        }
        $list = [];
        foreach (glob($rule) as $file) {
            $list[] = $file;
        }
        \Cache::tags(['duxravel-run'])->put('file-' . $rule, $list);
        return $list;
    }

}