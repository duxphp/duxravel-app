<?php

namespace Duxravel\Core\Util;

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
        $files = new Filesystem();
        $bulidPath = base_path('bootstrap/cache/duxravel.php');
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
        foreach ($list as $key => $vo) {
            if (isset($vo['service']) && $vo['service']) {
                $vo['service'] = is_array($vo['service']) ? $vo['service'] : [$vo['service']];
                foreach ($vo['service'] as $v) {
                    $v = explode('\\', $v);
                    $name = end($v);
                    if ($name) {
                        $service[$name][] = $v;
                    }
                }
            }
            if (isset($vo['route']) && $vo['route']) {
                $vo['route'] = is_array($vo['route']) ? $vo['route'] : [$vo['route']];
                foreach ($vo['route'] as $v) {
                    $file = $key . '/' . $v;
                    $name = basename($file, '.php');
                    if ($name) {
                        $route[$name][] = $file;
                    }
                }
            }
        }

        if (!is_writable($dirname = dirname($bulidPath))) {
            throw new Exception("The {$dirname} directory must be present and writable.");
        }

        $manifest = [
            'service' => array_filter($service),
            'route' => array_filter($route)
        ];
        $files->replace(
            $bulidPath, '<?php return ' . var_export($manifest, true) . ';'
        );
    }

    protected function format($package)
    {
        return str_replace(base_path('vendor') . '/', '', $package);
    }

}
