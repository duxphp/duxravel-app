<?php

namespace Duxravel\Core\Service;

/**
 * 标签扩展
 */
class Blade
{
    /**
     * 菜单标签
     * @param array $args
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection|\Kalnoy\Nestedset\Collection|\Modules\Tools\Model\ToolsMenuItems[]
     */
    public static function form(array $args = [])
    {
        $params = [
            'id' => $args['id'] ?: 1,
            'limit' => (int)$args['limit'] ?: 10,
            'page' => (bool)$args['page'],
        ];
        $data = new \Duxravel\Core\Model\FormData();
        $data = $data->where('status', 1);

        if ($params['page']) {
            $data = $data->paginate($params['limit']);
        } else {
            $data = $data->limit($params['limit'])->get();
        }
        return $data;

    }
}

