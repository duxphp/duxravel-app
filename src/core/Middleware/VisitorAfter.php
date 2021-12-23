<?php

namespace Duxravel\Core\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Jenssegers\Agent\Agent;

class VisitorAfter
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if (empty($request->route())) {
            abort(404);
        }
        if (!file_exists(storage_path('installed'))) {
            return $response;
        }
        if ($request->route()->getAction('statis')) {
            $this->api($request);
        }
        $this->operate($request);

        return $response;
    }

    /**
     * 操作日志
     * @param Request $request
     * @return bool
     */
    private function operate(Request $request)
    {
        $hasType = $request->route()->getAction('auth_has');
        if (!$hasType) {
            return false;
        }
        $hasId = auth($hasType)->user()->user_id;
        if (!$hasId) {
            return false;
        }
        $model = new \Duxravel\Core\Model\VisitorOperate();
        // 清理过期日志
        $time = now()->subDays(env('VISIATOR_OPERATE_DAY'))->toDateString();
        $model->where('created_at', '<=', $time)->delete();

        // 增加操作记录
        $params = $request->all();
        $agent = new Agent();
        $data = [
            'has_type' => $hasType,
            'has_id' => $hasId,
            'username' => auth($hasType)->user()->username,
            'method' => $request->method(),
            'route' => $request->getPathInfo(),
            'name' => $request->route()->getAction('as'),
            'desc' => $request->route()->getAction('desc') ?: '未知',
            'params' => $params,
            'ip' => $request->ip(),
            'ua' => $request->userAgent(),
            'device' => $agent->platform(),
            'browser' => $agent->browser(),
            'mobile' => $agent->isPhone(),
            'time' => round(microtime(true) - START_TIME, 3)
        ];
        DB::beginTransaction();
        try {
            $model->create($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return true;
    }

    /**
     * @param $request
     * @return void
     */
    private function api($request): void
    {

        $time = round(microtime(true) - START_TIME, 3);
        $data = [
            'method' => $request->method(),
            'name' => $request->route()->getAction('as'),
            'desc' => $request->route()->getAction('desc') ?: '未知',
            'date' => date('Ymd'),
        ];
        if (!$data['name']) {
            return;
        }
        $model = new \Duxravel\Core\Model\VisitorApi;
        $info = $model->where('name', $data['name'])->where('date', $data['date'])->first();
        DB::beginTransaction();
        try {
            // PV
            if ($info) {
                $data = [];
                if ($time <= $info->min_time) {
                    $data['min_time'] = $time;
                }
                if ($time >= $info->max_time) {
                    $data['max_time'] = $time;
                }

                $model->where('api_id', $info->api_id)->increment('pv', 1, $data);
                $id = $info['api_id'];
            } else {
                $data['min_time'] = $time;
                $data['max_time'] = $time;
                $id = $model->create($data)->api_id;
            }
            // UV
            $keys = [
                'method' => $data['method'],
                'name' => $data['name'],
                'ip' => $request->ip(),
                'ua' => $request->userAgent(),
            ];
            $key = 'app::api::'.sha1(implode(':', $keys));
            if (!Redis::get($key)) {
                Redis::setex($key, 86400 - (time() + 8 * 3600) % 86400, 1);
                \Duxravel\Core\Model\VisitorApi::where('api_id', $id)->increment('uv', 1);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

    }
}
