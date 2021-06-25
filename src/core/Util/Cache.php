<?php

namespace Duxravel\Core\Util;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * 系统缓存
 */
class Cache
{

    /**
     * 缓存文件
     * @param $rule
     * @return array
     */
    public static function globList($rule)
    {
        $cacheList = cache()->get('file-' . $rule);
        if ($cacheList) {
            return $cacheList;
        }
        $list = [];
        foreach (glob($rule) as $file) {
            $list[] = $file;
        }
        cache()->put('file-' . $rule, $list);
        return $list;
    }

    public static function build()
    {
        $packages = [];
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
            return [self::format($package['name']) => $package['extra']['duxravel'] ?? []];
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

    protected static function format($package)
    {
        return str_replace(base_path('vendor') . '/', '', $package);
    }

}
