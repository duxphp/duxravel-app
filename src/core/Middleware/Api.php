<?php

namespace Duxravel\Core\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class Api
{
    /**
     * 误差秒
     * @var int
     */
    protected int $time = 5;

    public function handle(Request $request, Closure $next)
    {
        // 请求超时
        if (!$this->allowTimestamp($request)) {
            app_error('Request Timeout', 408);
        }
        // 签名失败
        if (!$this->signVerify($request)) {
            app_error('Sign Failed', 402);
        }
        return $next($request);
    }

    /**
     * 签名验证
     * @param $request
     * @return bool
     */
    protected function signVerify(Request $request): bool
    {
        $time = $request->header('Content-Date');
        $sign = $request->header('Content-MD5');
        $id = $request->header('AccessKey');

        if (empty($id) || empty($sign) || empty($time)) {
            app_error('Parameter signature failed', 402);
        }

        $apiInfo = \Duxravel\Core\Model\Api::where('secret_id', $id)->firstOr(function () {
            app_error('Signature authorization failed', 402);
        });
        $secretKey = $apiInfo->secret_key;

        $url = $request->getSchemeAndHttpHost() . $_SERVER["REQUEST_URI"];
        $signStr = "url={$url}&timestamp={$time}&key={$secretKey}";
        if (strtoupper(md5($signStr)) === $sign) {
            return true;
        }
        return false;
    }

    /**
     * 判断时差
     * @param Request $request
     * @return bool
     */
    protected function allowTimestamp(Request $request): bool
    {
        $queryTime = $request->header('Content-Date', 0);
        if ($queryTime + $this->time < time()) {
            return false;
        }
        return true;
    }
}
