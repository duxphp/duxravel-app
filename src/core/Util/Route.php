<?php

namespace Duxravel\Core\Util;

/**
 * 路由扩展方法
 */
class Route
{

    private array $action = ['index', 'data', 'page', 'save', 'del', 'recovery', 'clear', 'status', 'export'];
    private string $app;
    private string $layout;
    private string $name;
    private string $class;
    private string $prefix;

    public function __construct(string $class, string $name = '')
    {
        $classData = explode('\\', str_replace('/', '\\', $class), 4);
        [$module, $app, $layout, $as] = $classData;
        if (!$app || !$layout || !$as) {
            throw new \ErrorException('Route class resolution failed');
        }
        $this->app = lcfirst($app);
        $this->layout = lcfirst($layout);
        $this->name = lcfirst($name ?: $as);
        $this->prefix = $this->name;
        $this->class = $class;
    }

    /**
     * 指定前缀
     * @param $name
     * @return $this
     */
    public function prefix($name): self
    {
        $this->prefix = $name;
        return $this;
    }

    /**
     * 仅允许
     * @param $name
     * @return $this
     */
    public function only($name): self
    {
        $name = is_array($name) ? $name : [$name];
        $this->action = $name;
        return $this;
    }

    /**
     * 排除方法
     * @param $name
     * @return $this
     */
    public function except($name): self
    {
        $name = !is_array($name) ? $name : [$name];
        $this->action = array_diff($this->action, $name);
        return $this;
    }

    public function make(): void
    {
        $collection = collect();
        foreach ($this->action as $action) {
            $route = $this->{'addItem' . ucfirst($action)}($this->app, $this->layout, $this->name, $this->class);
            $collection->add($route);
        }
        $collection->map(function ($item) {
            \Route::{$item['type']}($item['rule'], ['uses' => $item['uses'], 'desc' => $item['desc']])->name($item['name']);
        });
    }

    /**
     * @param $app
     * @param $layout
     * @param $name
     * @param $class
     * @return array
     */
    private function addItemIndex($app, $layout, $name, $class)
    {
        return [
            'type' => 'get',
            'rule' => $this->prefix,
            'uses' => $class . '@' . 'index',
            'desc' => '列表页面',
            'name' => implode('.', [$layout, $app, $name])
        ];
    }

    /**
     * @param $app
     * @param $layout
     * @param $name
     * @param $class
     * @return array
     */
    private function addItemData($app, $layout, $name, $class)
    {
        return [
            'type' => 'get',
            'rule' => $this->prefix . '/data',
            'uses' => $class . '@data',
            'desc' => '列表数据',
            'name' => implode('.', [$layout, $app, $name, 'data'])
        ];
    }

    /**
     * @param $app
     * @param $layout
     * @param $name
     * @param $class
     * @return array
     */
    private function addItemPage($app, $layout, $name, $class)
    {
        return [
            'type' => 'get',
            'rule' => $this->prefix . '/page/{id?}',
            'uses' => $class . '@page',
            'desc' => '保存页面',
            'name' => implode('.', [$layout, $app, $name, 'page'])
        ];
    }

    /**
     * @param $app
     * @param $layout
     * @param $name
     * @param $class
     * @return array
     */
    private function addItemSave($app, $layout, $name, $class)
    {
        return [
            'type' => 'post',
            'rule' => $this->prefix . '/save/{id?}',
            'uses' => $class . '@save',
            'desc' => '保存数据',
            'name' => implode('.', [$layout, $app, $name, 'save'])
        ];
    }

    /**
     * @param $app
     * @param $layout
     * @param $name
     * @param $class
     * @return array
     */
    private function addItemDel($app, $layout, $name, $class)
    {
        return [
            'type' => 'post',
            'rule' => $this->prefix . '/del/{id?}',
            'uses' => $class . '@del',
            'desc' => '删除',
            'name' => implode('.', [$layout, $app, $name, 'del'])
        ];
    }

    /**
     * @param $app
     * @param $layout
     * @param $name
     * @param $class
     * @return array
     */
    private function addItemRecovery($app, $layout, $name, $class)
    {
        return [
            'type' => 'get',
            'rule' => $this->prefix . '/recovery/{id?}',
            'uses' => $class . '@recovery',
            'desc' => '恢复',
            'name' => implode('.', [$layout, $app, $name, 'recovery'])
        ];
    }

    /**
     * @param $app
     * @param $layout
     * @param $name
     * @param $class
     * @return array
     */
    private function addItemClear($app, $layout, $name, $class)
    {
        return [
            'type' => 'post',
            'rule' => $this->prefix . '/clear/{id?}',
            'uses' => $class . '@clear',
            'desc' => '彻底删除',
            'name' => implode('.', [$layout, $app, $name, 'clear'])
        ];
    }

    /**
     * @param $app
     * @param $layout
     * @param $name
     * @param $class
     * @return array
     */
    private function addItemStatus($app, $layout, $name, $class)
    {
        return [
            'type' => 'post',
            'rule' => $this->prefix . '/status/{id?}',
            'uses' => $class . '@status',
            'desc' => '状态',
            'name' => implode('.', [$layout, $app, $name, 'status'])
        ];
    }

    private function addItemexport($app, $layout, $name, $class)
    {
        return [
            'type' => 'get',
            'rule' => $this->prefix . '/export',
            'uses' => $class . '@export',
            'desc' => '导出',
            'name' => implode('.', [$layout, $app, $name, 'export'])
        ];
    }

}

