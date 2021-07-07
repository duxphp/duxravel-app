<?php

namespace Duxravel\Core\UI;

/**
 * UI工具
 * Class Table
 * @package Duxravel\Core\UI
 */
class Tools
{

    /**
     * 解析对象数据
     * @param object $data
     * @param string $relation
     * @param string|null $field
     * @return array|\ArrayAccess|mixed|string
     */
    public static function parsingObjData(object $data, string $relation, string $field = null)
    {
        $relationData = $data->$relation;
        if ($field) {
            if ($relationData instanceof \Illuminate\Support\Collection) {
                $tmp = [];
                foreach ($relationData as $vo) {
                    $tmp[] = self::parsingArrData($vo, $field);
                }
                $data = implode(',', $tmp);
            } else {
                $data = self::parsingArrData($relationData, $field);
            }
        } else {
            $data = '';
        }
        return $data;
    }

    /**
     * 解析数组数据
     * @param $data
     * @param string|null $field
     * @param bool $source
     * @return string|string[]|null
     */
    public static function parsingArrData($data, string $field = null, bool $source = false)
    {
        $field = str_replace('->', '.', $field);
        if (!$source) {
            return $field ? \Arr::get($data, $field) : '';
        }
        return \Arr::has($data, $field) ? \Arr::get($data, $field) : $field;
    }

    /**
     * 属性转换
     * @param $data
     * @return string
     */
    public static function toAttr($data)
    {
        $attr = [];
        foreach ($data as $name => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            if (strpos($value, '"') === false) {
                $attr[] = "{$name}=\"{$value}\"";
            } else {
                $attr[] = "{$name}='{$value}'";
            }
        }
        return implode(' ', $attr);
    }


    /**
     * 样式类转换
     * @param $class
     * @param false $inner
     * @return string
     */
    public static function toClass($class, bool $inner = false): string
    {
        return $inner ? implode(' ', $class) : 'class="' . implode(' ', $class) . '"';
    }

    /**
     * 样式转换
     * @param $data
     * @param false $inner
     * @return string
     */
    public static function toStyle($data, bool $inner = false): string
    {
        $style = [];
        foreach ($data as $name => $value) {
            $style[] = $name . ':' . $value;
        }
        return $inner ? implode(';', $style) : 'style="' . implode(';', $style) . '"';
    }

    /**
     * 权限判断
     * @param $auth
     * @return bool
     */
    public static function isAuth($auth): bool
    {
        if (!$auth) {
            return true;
        }
        $public = app('router')->getRoutes()->getByName($auth)->getAction('public');
        $app = \Str::before($auth, '.');
        if ($app <> app()->make('purview_app') || $public) {
            return true;
        }
        $purview = app()->make('purview');
        if (!$purview) {
            return true;
        }
        if (!in_array($auth, $purview)) {
            return false;
        }
        return true;
    }

}
