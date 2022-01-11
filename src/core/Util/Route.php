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
    private array $extend = [];

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
     * @param string $name
     * @return $this
     */
    public function prefix(string $name): self
    {
        $this->prefix = $name;
        return $this;
    }

    /**
     * 仅允许
     * @param string|array $name
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
     * @param string|array $name
     * @return $this
     */
    public function except($name): self
    {
        $name = !is_array($name) ? $name : [$name];
        $this->action = array_diff($this->action, $name);
        return $this;
    }

    /**
     * 追加方法
     * @param string|array $name
     * @return $this
     */
    public function append($name): self
    {
        $name = is_array($name) ? $name : [$name];
        $this->action = array_merge($this->action, $name);
        return $this;
    }

    /**
     * 生成路由
     */
    public function make(): void
    {
        $collection = collect();
        foreach ($this->action as $action) {
            $route = $this->{'addItem' . ucfirst($action)}($this->app, $this->layout, $this->name, $this->class);
            $collection->add($route);
        }
        $collection->map(function ($item) {
            if (!$item[0]) {
                $item = [$item];
            }
            foreach ($item as $vo) {
                \Route::match(is_array($vo['type']) ? $vo['type'] : [$vo['type']], $vo['rule'], ['uses' => $vo['uses'], 'desc' => $vo['desc'], 'auth_list' => isset($vo['auth_list']) ? $vo['auth_list'] : []])->name($vo['name']);
            }
        });
    }

    /**
     * @param string $app
     * @param string $layout
     * @param string $name
     * @param string $class
     * @return array
     */
    private function addItemIndex(string $app, string $layout, string $name, string $class): array
    {
        return [
            [
                'type' => 'get',
                'rule' => $this->prefix,
                'uses' => $class . '@' . 'index',
                'desc' => '列表页面',
                'name' => implode('.', [$layout, $app, $name])
            ],
            [
                'type' => 'get',
                'rule' => $this->prefix . '/ajax',
                'uses' => $class . '@ajax',
                'desc' => 'ajax数据',
                'name' => implode('.', [$layout, $app, $name, 'ajax'])
            ]
        ];
    }

    /**
     * @param string $app
     * @param string $layout
     * @param string $name
     * @param string $class
     * @return array
     */
    private function addItemData(string $app, string $layout, string $name, string $class): array
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
     * @param string $app
     * @param string $layout
     * @param string $name
     * @param string $class
     * @return array
     */
    private function addItemPage(string $app, string $layout, string $name, string $class): array
    {
        return [
            'type' => 'get',
            'rule' => $this->prefix . '/page/{id?}',
            'uses' => $class . '@page',
            'desc' => '保存页面',
            'auth_list' => ['add' => '添加页面', 'edit' => '编辑页面'],
            'name' => implode('.', [$layout, $app, $name, 'page'])
        ];
    }

    /**
     * @param string $app
     * @param string $layout
     * @param string $name
     * @param string $class
     * @return array
     */
    private function addItemSave(string $app, string $layout, string $name, string $class): array
    {
        return [
            'type' => 'post',
            'rule' => $this->prefix . '/save/{id?}',
            'uses' => $class . '@save',
            'desc' => '保存数据',
            'auth_list' => ['add' => '添加数据', 'edit' => '编辑数据'],
            'name' => implode('.', [$layout, $app, $name, 'save'])
        ];
    }

    /**
     * @param string $app
     * @param string $layout
     * @param string $name
     * @param string $class
     * @return array
     */
    private function addItemDel(string $app, string $layout, string $name, string $class): array
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
     * @param string $app
     * @param string $layout
     * @param string $name
     * @param string $class
     * @return array
     */
    private function addItemRecovery(string $app, string $layout, string $name, string $class): array
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
     * @param string $app
     * @param string $layout
     * @param string $name
     * @param string $class
     * @return array
     */
    private function addItemClear(string $app, string $layout, string $name, string $class): array
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
     * @param string $app
     * @param string $layout
     * @param string $name
     * @param string $class
     * @return array
     */
    private function addItemStatus(string $app, string $layout, string $name, string $class): array
    {
        return [
            'type' => 'post',
            'rule' => $this->prefix . '/status/{id?}',
            'uses' => $class . '@status',
            'desc' => '状态',
            'name' => implode('.', [$layout, $app, $name, 'status'])
        ];
    }

    /**
     * @param string $app
     * @param string $layout
     * @param string $name
     * @param string $class
     * @return array
     */
    private function addItemexport(string $app, string $layout, string $name, string $class): array
    {
        return [
            'type' => 'get',
            'rule' => $this->prefix . '/export',
            'uses' => $class . '@export',
            'desc' => '导出',
            'name' => implode('.', [$layout, $app, $name, 'export'])
        ];
    }

    /**
     * @param string $app
     * @param string $layout
     * @param string $name
     * @param string $class
     * @return array
     */
    private function addItemSort(string $app, string $layout, string $name, string $class): array
    {
        return [
            'type' => 'post',
            'rule' => $this->prefix . '/sortable',
            'uses' => $class . '@sortable',
            'desc' => '排序',
            'name' => implode('.', [$layout, $app, $name, 'sortable'])
        ];
    }


    /**
     * 自定义方法
     * @param string|array $type
     * @param string $action
     * @param string $name
     * @return $this
     */
    public function add($type, string $action, string $name): Route
    {
        $this->extend['addItem' . ucfirst($action)] = [
            'type' => $type,
            'action' => $action,
            'name' => $name,
        ];
        $this->action = array_merge($this->action, [$action]);
        return $this;
    }

    /**
     * @param $method
     * @param $arguments
     * @return array
     * @throws \Exception
     */
    public function __call($method, $arguments)
    {
        if (!isset($this->extend[$method])) {
            throw new \Exception('There is no route method "' . $method . '"');
        }

        [$app, $layout, $name, $class] = $arguments;

        $info = $this->extend[$method];
        unset($this->extend[$method]);
        return [
            'type' => $info['type'],
            'rule' => $this->prefix . '/' . $info['action'],
            'uses' => $class . '@' . $info['action'],
            'desc' => $info['name'],
            'name' => implode('.', [$layout, $app, $name, $info['action']])
        ];

    }

}

