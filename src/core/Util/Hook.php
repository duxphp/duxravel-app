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
            return null;
        }
        $layer = ucfirst($layer);
        $apiPath = base_path('modules') . '/*/' . $layer . '/' . ucfirst($name) . '.php';
        $apiList = glob($apiPath);
        if (empty($apiList)) {
            return [];
        }
        $list = [];
        foreach ($apiList as $value) {
            $path = substr($value, strlen(base_path('modules') . '/'), -4);
            $path = str_replace('\\', '/', $path);
            $class = '\\Modules\\' . str_replace('/', '\\', $path);
            if (!class_exists($class)) {
                continue;
            }
            $class = new $class;
            if (method_exists($class, $method)) {
                $data[] = call_user_func_array([$class, $method], $vars);
            }
            $list[] = $class;
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