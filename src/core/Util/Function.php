<?php

if (!function_exists("app_success")) {

    /**
     * 成功处理
     * @param string $msg
     * @param array $data
     * @param string $url
     * @param int $code
     * @return array|\Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    function app_success(string $msg = '', array $data = [], string $url = '', int $code = 200)
    {
        if (request()->expectsJson()) {
            return Response::success($data, $msg, $code, ['x-Location' => $url ?: ''], JSON_NUMERIC_CHECK);
        } else {
            return response()->view('vendor.duxphp.duxravel-app.src.core.Views.success', [
                'msg' => $msg,
                'url' => $url
            ], 200);
        }
    }
}

if (!function_exists("app_error")) {

    /**
     * 错误处理
     * @param          $msg
     * @param int $code
     * @param string $url
     */
    function app_error($msg, int $code = 500, string $url = '')
    {
        if (request()->expectsJson()) {
            abort($code, $msg, ['x-location' => $url]);
        } else {
            throw new \Duxravel\Core\Exceptions\ErrorException($msg, $code, $url);
        }
    }
}

if (!function_exists("app_error_if")) {

    /**
     * 错误处理条件
     * @param $boolean
     * @param $msg
     * @param int $code
     * @param string $url
     */
    function app_error_if($boolean, $msg, int $code = 500, string $url = '')
    {
        if ($boolean) {
            app_error($code, $msg, ['x-location' => $url]);
        }
    }
}

if (!function_exists("app_parsing")) {

    /**
     * 获取应用解析
     * @param string $key
     * @return array|string
     */
    function app_parsing(string $key = '')
    {
        $action = request()->route()->getActionName();
        $relativeDir = str_replace('Modules\\', '', $action);
        [$dir, $action] = explode('@', $relativeDir);
        $dirs = explode('\\', $dir);
        $data = [
            'app' => $dirs[0],
            'layer' => $dirs[1],
            'module' => $dirs[2],
            'action' => $action
        ];
        if (empty($key)) {
            return $data;
        }
        return $data[$key];
    }
}


if (!function_exists("module")) {

    /**
     * 类对象
     * @param string $class
     * @return mixed
     */
    function module(string $class)
    {
        $class = '\\Modules\\' . str_replace('.', '\\', $class);
        return app($class);
    }
}


if (!function_exists("app_route")) {

    /**
     * 生成符合前端解析的url
     */
    function app_route($route, $params = [], $absolute = true, $model = '', $field = [])
    {
        $paramsFix = [];
        $paramsModel = [];
        $data = [];
        // 解析{}变量
        foreach ($params as $k => $v) {
            preg_match('/(?:\{)(.*)(?:\})/i', $v, $match);
            if ($match[1]) {
                $paramsFix[$k] = $match[1];
                $data[$k] = 'Ss' . $k . 'sS';
            } else {
                if (in_array($v, $field)) {
                    $paramsModel[$k] = $v;
                    $data[$k] = 'Ss' . $k . 'sS';
                } else {
                    $data[$k] = $v;
                }
            }
        }

        $url = route($route, $data, $absolute);

        // 解析js变量
        foreach ($paramsFix as $k => $v) {
            $url = str_replace('Ss' . $k . 'sS', '${' . $v . ' || \'\'}', $url);
        }
        foreach ($paramsModel as $k => $v) {
            $url = str_replace('Ss' . $k . 'sS', '${' . ($model ? $model . '.' : '') . $v . '|| \'\'}', $url);
        }
        $url = "`$url`";
        return $url;
    }
}

if (!function_exists("app_filesize")) {
    /**
     * 文件大小转换
     * @param $num
     * @return string
     */
    function app_filesize($num)
    {
        $p = 0;
        $format = 'bytes';
        if ($num > 0 && $num < 1024) {
            $p = 0;
            return number_format($num) . ' ' . $format;
        }
        if ($num >= 1024 && $num < pow(1024, 2)) {
            $p = 1;
            $format = 'KB';
        }
        if ($num >= pow(1024, 2) && $num < pow(1024, 3)) {
            $p = 2;
            $format = 'MB';
        }
        if ($num >= pow(1024, 3) && $num < pow(1024, 4)) {
            $p = 3;
            $format = 'GB';
        }
        if ($num >= pow(1024, 4) && $num < pow(1024, 5)) {
            $p = 3;
            $format = 'TB';
        }
        $num /= pow(1024, $p);
        return number_format($num, 3) . ' ' . $format;
    }
}


if (!function_exists('module_path')) {
    function module_path($path = ''): string
    {
        return base_path('modules/' . $path);
    }
}

if (!function_exists('get_uuid')) {
    function get_uuid($len = 0): string
    {
        $int = '';
        while (strlen($int) != $len) {
            $int .= mt_rand(0, 9);
        }
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8) . $int;
    }
}

if (!function_exists('html_text')) {
    function html_text($html, $len = 0): string
    {
        $html = trim($html);
        $html = strip_tags($html, "");
        $text = str_replace([" ", "　", "\t", "\n", "\r"], '', $html);
        $text = rtrim($text, ",");
        $text = rtrim($text, "，");
        $text = rtrim($text, ".");
        $text = rtrim($text, "，");
        return $len ? Str::limit($text, $len, '') : $text;
    }
}

if (!function_exists('file_class')) {
    function file_class($file): string
    {
        if(is_file($file)) {
            return '';
        }
        $tokens = token_get_all(file_get_contents($file));
        $namespace = '';
        for ($index = 0; isset($tokens[$index]); $index++) {
            if (!isset($tokens[$index][0])) {
                continue;
            }
            if (T_NAMESPACE === $tokens[$index][0]) {
                $index += 2;
                while (isset($tokens[$index]) && is_array($tokens[$index])) {
                    $namespace .= $tokens[$index++][1];
                }
            }
            if (T_CLASS === $tokens[$index][0] && T_WHITESPACE === $tokens[$index + 1][0] && T_STRING === $tokens[$index + 2][0]) {
                $index += 2;
                $namespace = $namespace.'\\'.$tokens[$index][1];
                break;
            }
        }
        return $namespace;
    }
}


if (!function_exists('url_class')) {
    function url_class($url): array
    {
        $request = [];
        try {
            $request = app('router')->getRoutes()->match(app('request')->create($url))->getAction();
        }catch (\Exception $exception) {
        }
        [$class, $action] = explode('@', $request['controller']);
        return [
            'class' => $class,
            'action' => $action
        ];
    }
}
