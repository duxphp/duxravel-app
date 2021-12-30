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
        $providers = [];

        $serviceList = $this->globList(base_path('modules') . '/*/Service/*.php');
        $menuList = $this->globList(base_path('modules') . '/*/Menu/*.php');
        $eventList = $this->globList(base_path('modules') . '/*/Events/*.php');
        $listenerList = $this->globList(base_path('modules') . '/*/Listeners/*.php');
        $roleList = $this->globList(base_path('modules') . '/*/Route/*.php');
        $configList = $this->globList(base_path('modules') . '/*/Config/*.php');
        $providersList = $this->globList(base_path('modules') . '/*/Providers/*.php');
        $list['modules'] = [
            'service' => [],
            'route' => [],
            'menu' => [],
            'config' => [],
            'event' => [],
            'listener' => [],
            'providers' => []
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
        $list['modules']['providers'] = $this->formatClass($providersList);

        foreach ($list as $key => $vo) {
            if (isset($vo['event']) && $vo['event']) {
                $vo['event'] = is_array($vo['event']) ? $vo['event'] : [$vo['event']];
                foreach ($vo['event'] as $v) {
                    $event[] = $v;
                }
            }
            if (isset($vo['service']) && $vo['service']) {
                $service = array_merge_recursive($service, $this->getClassData($vo['service']));
            }
            if (isset($vo['listener']) && $vo['listener']) {
                $listener = array_merge_recursive($listener, $this->getClassData($vo['listener']));
            }
            if (isset($vo['providers']) && $vo['providers']) {
                $providers = array_merge_recursive($providers, $this->getClassData($vo['providers']));
            }
            if (isset($vo['route']) && $vo['route']) {
                $route = array_merge_recursive($route, $this->getFileData($vo['route'], $key));
            }
            if (isset($vo['config']) && $vo['config']) {
                $config = array_merge_recursive($config, $this->getFileData($vo['config'], $key));
            }
            if (isset($vo['menu']) && $vo['menu']) {
                $menu = array_merge_recursive($menu, $this->getFileData($vo['menu'], $key));
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
            'menu' => array_filter($menu),
            'providers' => array_filter($providers)
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

    /**
     * @param $data
     * @param $key
     * @return array
     */
    private function getFileData($data, $key)
    {
        $data = is_array($data) ? $data : [$data];
        $array = [];
        foreach ($data as $v) {
            $file = ($key === 'modules' ? 'modules' : 'vendor' . '/' . $key) . '/' . trim($v, '/');
            $name = basename($file, '.php');
            if ($name) {
                $array[$name][] = $file;
            }
        }
        return $array;
    }

    /**
     * @param $data
     * @return array
     */
    private function getClassData($data)
    {
        $data = is_array($data) ? $data : [$data];
        $array = [];
        foreach ($data as $v) {
            $tmp = explode('\\', $v);
            $name = end($tmp);
            if ($name) {
                $array[$name][] = $v;
            }
        }
        return $array;
    }

}
