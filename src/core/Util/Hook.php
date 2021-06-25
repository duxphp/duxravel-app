<?php

namespace Duxravel\Core\Util;

/**
 * 钩子工具
 */
class Hook
{
    private $data = [];

    /**
     * @param string $type
     * @param string $name
     * @param string $method
     * @param callable $menu
     */
    public function add(string $type, string $name, string $method, callable $menu)
    {
        $this->data[$this->getKey($type, $name, $method)][] = $menu;
    }

    /**
     * @param string $type
     * @param string $name
     * @param string $method
     * @return array
     */
    public function get(string $type, string $name, string $method): array
    {
        $key = $this->getKey($type, $name, $method);
        $data = $this->data[$key] ?? [];
        $list = [];
        foreach ($data as $key => $vo) {
            $list[$key] = $vo();
        }
        return $list ?: [];
    }

    /**
     * @param string $layer
     * @param string $name
     * @param string $method
     * @param array $vars
     * @return array
     */
    public function getAll(string $layer, string $name, string $method, array $vars = [])
    {
        if (empty($name)) {
            return [];
        }
        $layer = ucfirst($layer);

        $key = 'hook-' . $this->getKey($layer, $name, $method);
        $cacheList = cache()->get($key);
        $list = [];
        if (!$cacheList) {
            $apiPath = base_path('modules') . '/*/' . $layer . '/' . ucfirst($name) . '.php';
            $apiList = glob($apiPath);
            if (!empty($apiList)) {
                foreach ($apiList as $value) {
                    $path = substr($value, strlen(base_path('modules') . '/'), -4);
                    $path = str_replace('\\', '/', $path);
                    $class = '\\Modules\\' . str_replace('/', '\\', $path);
                    if (!class_exists($class)) {
                        continue;
                    }
                    $class = new $class;
                    if (method_exists($class, $method)) {
                        $list[] = [$class, $method];
                    }
                }
            }
            cache()->put($key, $list);
        } else {
            $list = $cacheList;
        }
        foreach ($list as $vo) {
            $list[] = call_user_func_array([$vo[0], $vo[1]], $vars);
        }

        $extend = $this->get($layer, $name, $method);
        return array_filter(array_merge($extend, $list));

    }

    /**
     * @param string $type
     * @param string $name
     * @param string $method
     * @return string
     */
    private function getKey(string $type, string $name, string $method): string
    {
        return strtolower($type) . ':' . strtolower($name) . ':' . strtolower($method);
    }

}