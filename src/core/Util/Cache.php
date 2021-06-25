<?php

namespace Duxravel\Core\Util;

use Illuminate\Filesystem\Filesystem;

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
        foreach ($list as $vo) {
            if (isset($vo['service']) && $vo['service']) {
                $vo['service'] = is_array($vo['service']) ? $vo['service'] : [$vo['service']];
                $service = array_merge($service, $vo['service']);
            }
            if (isset($vo['route']) && $vo['route']) {
                $vo['route'] = is_array($vo['route']) ? $vo['route'] : [$vo['route']];
                $route = array_merge($route, $vo['route']);
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
