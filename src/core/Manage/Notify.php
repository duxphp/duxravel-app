<?php

namespace Duxravel\Core\Manage;

use Illuminate\Http\Request;

/**
 * 消息通知
 */
trait Notify
{

    public function getNotify()
    {
        $type = request()->get('type', 0);
        $layer = strtolower(app_parsing('layer'));
        $model = auth($layer)->user();
        if ($type) {
            $list = $model->notifications();
        } else {
            $list = $model->unreadNotifications();
        }
        $list = $list->where('type', \Duxravel\Core\Notifications\Manage::class)->get();
        $data = [];
        foreach ($list as $vo) {
            $data[] = [
                'uuid' => $vo->id,
                'time' => $vo->created_at->format('Y-m-d H:i:s'),
                'read' => $vo->read_at ? true : false,
                'message' => $vo->data['invoice'],
            ];
        }
        return app_success('ok', [
            'list' => $data,
            'num' => $model->unreadNotifications->count()
        ]);
    }

    public function readNotify()
    {
        $uuid = request()->get('uuid');
        $layer = strtolower(app_parsing('layer'));
        $model = auth($layer)->user();
        if ($uuid) {
            $model->unreadNotifications()->where('id', $uuid)->update(['read_at' => now()]);
        }else {
            $model->unreadNotifications->markAsRead();
        }
    }


    public function delNotify()
    {
        $uuid = request()->get('uuid');
        $layer = strtolower(app_parsing('layer'));
        $model = auth($layer)->user();
        if ($uuid) {
            $model->notifications()->where('id', $uuid)->delete();
        }else {
            $model->notifications()->delete();
        }
    }
}
