<?php

namespace Duxravel\Core\Util;

use Duxravel\Core\UI\Widget\Icon;

/**
 * 菜单存储
 */
class MenuStore
{
    private $data = [];
    private $pushData = [];
    private $appData = [];

    private $lastIndex = null;
    private $lastKey = 0;


    /**
     * 添加主菜单
     * @param $index
     * @param array $params
     * @param null $rule
     */
    public function add(string $index, array $params, $rule = null)
    {
        $this->lastIndex = $index;
        $this->lastKey = 0;
        $this->data[$index] = $params;

        if ($rule instanceof \Closure) {
            $rule($this);
        }
        if (is_string($rule)) {
            $this->data[$index]['route'] = $rule;
        }
    }

    /**
     * 添加菜单组
     * @param array $params
     * @param callable|string $menus
     */
    public function group(array $params, ?callable $callback = null, $index = null)
    {
        $lastGroup = $this->data[$this->lastIndex];
        if ($index) {
            $lastGroup['menu'][$index] = $params;
        } else {
            $lastGroup['menu'][] = $params;
        }
        $this->data[$this->lastIndex] = $lastGroup;
        $this->lastKey = $index ?: array_key_last($lastGroup['menu']);

        if ($callback instanceof \Closure) {
            $callback($this);
        }
    }


    /**
     * 添加菜单链接
     * @param string $name
     * @param string $route
     * @param array $params
     * @param int $index
     */
    public function link(string $name, string $route, array $params = [], int $index = 0)
    {
        $data = [
            'name' => $name,
            'route' => $route,
            'params' => $params,
            'order' => $index,
        ];
        $this->data[$this->lastIndex]['menu'][$this->lastKey]['menu'][] = $data;
    }

    /**
     * 添加app菜单
     * @param array $data
     * @param string $index
     */
    public function app(array $data)
    {
        $this->appData[] = $data;
    }

        /**
     * 追加菜单
     * @param string $index
     * @param callable $callback
     */
    public function push(string $index, callable $callback)
    {
        $this->pushData[] = [
            'index' => $index,
            'callback' => $callback
        ];
    }

    /**
     * 获取菜单数据
     * @return array
     */
    public function getData()
    {
        foreach ($this->pushData as $vo) {
            $deep = explode('.', $vo['index']);
            $this->lastIndex = $deep[0];
            $this->lastKey = $deep[1] ?: 0;
            $vo['callback']($this);
        }
        return $this->data;
    }

    /**
     * 获取app数据
     * @param string $name
     * @return mixed
     */
    public function getApps()
    {
        return $this->appData;
    }

}
