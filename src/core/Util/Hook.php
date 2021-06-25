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

        $list = [];
        $hookPath = base_path('modules') . '/*/Service/' . ucfirst($name) . '.php';
        $hookList = glob($hookPath);

        foreach ($hookList as $value) {
            $path = substr($value, strlen(base_path('modules') . '/'), -4);
            $path = str_replace('\\', '/', $path);
            $class = '\\Modules\\' . str_replace('/', '\\', $path);
            if (!class_exists($class)) {
                continue;
            }
            $list[] = $class;
        }

        $buildList = app(Build::class)->getData('service.' . $name);

        $list = array_filter(array_merge($buildList, $list));
        $data = [];
        foreach ($list as $class) {
            $class = new $class;
            if (method_exists($class, $method)) {
                $data[] = call_user_func_array([$class, $method], $vars);
            }
        }
        return array_filter($data);
    }

}