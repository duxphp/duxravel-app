<?php

namespace Duxravel\Core\Util;

/**
 * 钩子工具
 */
class Hook
{
    /**
     * @param string $name
     * @param string $method
     * @param array $vars
     * @return array
     */
    public function getAll(string $name, string $method, array $vars = []): array
    {
        if (empty($name)) {
            return [];
        }
        $list = app(Build::class)->getData('service.' . ucfirst($name));
        $data = [];
        foreach ($list as $class) {
            if (!class_exists($class)) {
                continue;
            }
            $class = new $class;
            if (method_exists($class, $method)) {
                $data[] = call_user_func_array([$class, $method], $vars);
            }
        }
        return array_filter($data);
    }

}