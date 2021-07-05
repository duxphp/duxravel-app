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

    private array $data = [];

    /**
     * 获取编译
     * @return mixed
     */
    public function getBuild()
    {
        if ($this->data) {
            return $this->data;
        }
        if (!is_file(base_path('bootstrap/cache/duxravel.php'))) {
            $this->build();
        }
        $this->data = require_once base_path('bootstrap/cache/duxravel.php');
        return $this->data;
    }

    public function getData($key)
    {
        $data = $this->getBuild();
        return \Arr::get($data, $key, []);
    }

    /**
     * 编译架构
     */
    public function build()
    {
        $buildPath = base_path('bootstrap/cache/duxravel.php');

        if (!is_writable($dirname = dirname($buildPath))) {
            throw new \Exception("The {$dirname} directory must be present and writable.");
        }

        \File::deleteDirectory($buildPath, true);
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
        $config = [];
        $serviceList = $this->globList(base_path('modules') . '/*/Service/*.php');
        $roleList = $this->globList(base_path('modules') . '/*/Route/*.php');
        $configList = $this->globList(base_path('modules') . '/*/Config/*.php');
        $list['modules'] = [
            'service' => [],
            'route' => [],
            'config' => []
        ];
        foreach ($roleList as $key => $vo) {
            $list['modules']['route'][] = str_replace(base_path('modules'), '', $vo);
        }
        foreach ($configList as $key => $vo) {
            $list['modules']['config'][] = str_replace(base_path('modules'), '', $vo);
        }
        foreach ($serviceList as $value) {
            $path = substr($value, strlen(base_path('modules') . '/'), -4);
            $path = str_replace('\\', '/', $path);
            $class = 'Modules\\' . str_replace('/', '\\', $path);
            if (!class_exists('\\' . $class)) {
                continue;
            }
            $list['modules']['service'][] = $class;
        }

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
        }

        $manifest = [
            'service' => array_filter($service),
            'route' => array_filter($route),
            'config' => array_filter($config)
        ];
        $files->replace(
            $buildPath, '<?php return ' . var_export($manifest, true) . ';'
        );
    }

    protected function format($package)
    {
        return str_replace(base_path('vendor') . '/', '', $package);
    }

    protected function globList($rule): array
    {
        $list = [];
        foreach (glob($rule) as $file) {
            $list[] = $file;
        }
        return $list;
    }

}
