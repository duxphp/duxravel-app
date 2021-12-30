<?php

namespace Duxravel\Core\Util;

use Doctrine\DBAL\Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * 基础编译
 */
class Build
{

    private $data = [];

    /**
     * 获取编译数据
     * @return array|mixed
     * @throws \Exception
     */
    public function getBuild()
    {
        if ($this->data) {
            return $this->data;
        }

        if (!is_file($buildPath = app()->bootstrapPath('cache/duxravel.php'))) {
            $this->build();
        }
        $this->data = require_once $buildPath;
        return $this->data;
    }

    /**
     * 获取数据
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public function getData($key)
    {
        $data = $this->getBuild();
        return \Arr::get($data, $key, []);
    }

    /**
     * 编译架构
     * @throws \Exception
     */
    public function build(): void
    {
        $buildPath = app()->bootstrapPath('cache/duxravel.php');

        if (!is_writable($dirname = dirname($buildPath))) {
            throw new \Exception("The {$dirname} directory must be present and writable.");
        }

        \File::delete($buildPath, true);
        $files = new Filesystem();
        $vendor = base_path('vendor');
        $packages = [];
        $path = $vendor . '/composer/installed.json';

        if ($files->exists($path)) {
            $installed = json_decode($files->get($path), true);
            $packages = $installed['packages'] ?? $installed;
        }

        $list = collect($packages)->mapWithKeys(function ($package) {
            return [$this->format($package['name']) => $package['extra']['duxravel'] ?? []];
        })->filter()->all();
        $service = [];
        $route = [];
        $menu = [];
        $config = [];
        $event = [];
        $listener = [];
        $serviceList = $this->globList(base_path('modules') . '/*/Service/*.php');
        $menuList = $this->globList(base_path('modules') . '/*/Menu/*.php');
        $eventList = $this->globList(base_path('modules') . '/*/Events/*.php');
        $listenerList = $this->globList(base_path('modules') . '/*/Listeners/*.php');
        $roleList = $this->globList(base_path('modules') . '/*/Route/*.php');
        $configList = $this->globList(base_path('modules') . '/*/Config/*.php');
        $list['modules'] = [
            'service' => [],
            'route' => [],
            'menu' => [],
            'config' => [],
            'event' => [],
            'listener' => [],
        ];
        foreach ($roleList as $key => $vo) {
            $list['modules']['route'][] = str_replace(base_path('modules'), '', $vo);
        }
        foreach ($configList as $key => $vo) {
            $list['modules']['config'][] = str_replace(base_path('modules'), '', $vo);
        }
        foreach ($menuList as $key => $vo) {
            $list['modules']['menu'][] = str_replace(base_path('modules'), '', $vo);
        }

        $list['modules']['service'] = $this->formatClass($serviceList);
        $list['modules']['event'] = $this->formatClass($eventList);
        $list['modules']['listener'] = $this->formatClass($listenerList);

        foreach ($list as $key => $vo) {
            if (isset($vo['service']) && $vo['service']) {
                $vo['service'] = is_array($vo['service']) ? $vo['service'] : [$vo['service']];
                foreach ($vo['service'] as $v) {
                    $tmp = explode('\\', $v);
                    $name = end($tmp);
                    if ($name) {
                        $service[$name][] = $v;
                    }
                }
            }
            if (isset($vo['event']) && $vo['event']) {
                $vo['event'] = is_array($vo['event']) ? $vo['event'] : [$vo['event']];
                foreach ($vo['event'] as $v) {
                    $event[] = $v;
                }
            }
            if (isset($vo['listener']) && $vo['listener']) {
                $vo['listener'] = is_array($vo['listener']) ? $vo['listener'] : [$vo['listener']];
                foreach ($vo['listener'] as $v) {
                    $tmp = explode('\\', $v);
                    $name = end($tmp);
                    if ($name) {
                        $listener[$name][] = $v;
                    }
                }
            }
            if (isset($vo['route']) && $vo['route']) {
                $vo['route'] = is_array($vo['route']) ? $vo['route'] : [$vo['route']];
                foreach ($vo['route'] as $v) {
                    $file = ($key === 'modules' ? 'modules' : 'vendor' . '/' . $key) . '/' . trim($v, '/');
                    $name = basename($file, '.php');
                    if ($name) {
                        $route[$name][] = $file;
                    }
                }
            }
            if (isset($vo['config']) && $vo['config']) {
                $vo['config'] = is_array($vo['config']) ? $vo['config'] : [$vo['config']];
                foreach ($vo['config'] as $v) {
                    $file = ($key === 'modules' ? 'modules' : 'vendor' . '/' . $key) . '/' . trim($v, '/');
                    $name = basename($file, '.php');
                    if ($name) {
                        $config[$name][] = $file;
                    }
                }
            }
            if (isset($vo['menu']) && $vo['menu']) {
                $vo['menu'] = is_array($vo['menu']) ? $vo['menu'] : [$vo['menu']];
                foreach ($vo['menu'] as $v) {
                    $file = ($key === 'modules' ? 'modules' : 'vendor' . '/' . $key) . '/' . trim($v, '/');
                    $name = basename($file, '.php');
                    if ($name) {
                        $menu[$name][] = $file;
                    }
                }
            }
        }

        $event = array_filter($event);
        $listener = array_filter($listener);
        $events = [];

        foreach ($event as $vo) {
            $tmp = explode('\\', $vo);
            $name = end($tmp);
            $events[$vo] = $listener[$name];
        }

        $manifest = [
            'service' => array_filter($service),
            'route' => array_filter($route),
            'config' => array_filter($config),
            'events' => array_filter($events),
            'menu' => array_filter($menu)
        ];
        $files->replace(
            $buildPath, '<?php return ' . var_export($manifest, true) . ';'
        );
    }

    /**
     * @param $package
     * @return string|string[]
     */
    protected function format($package)
    {
        return str_replace(base_path('vendor') . '/', '', $package);
    }

    /**
     * @param $rule
     * @return array
     */
    protected function globList($rule): array
    {
        $list = [];
        foreach (glob($rule) as $file) {
            $list[] = $file;
        }
        return $list;
    }

    /**
     * @param $list
     * @return string|void
     */
    private function formatClass($list)
    {
        $data = [];
        foreach ($list as $value) {
            $path = substr($value, strlen(base_path('modules') . '/'), -4);
            $path = str_replace('\\', '/', $path);
            $class = 'Modules\\' . str_replace('/', '\\', $path);
            if (!class_exists('\\' . $class)) {
                continue;
            }
            $data[] = $class;
        }
        return array_filter($data);
    }

}
