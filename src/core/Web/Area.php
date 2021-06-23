<?php

namespace Duxravel\Core\Web;

use App\Http\Controllers\Controller;
use Modules\System\Model\ToolsArea;

class Area extends Controller
{

    public function index()
    {
        $name = request()->get('query');
        $limit = request()->get('limit', 10);
        $id = request()->get('id');
        $parent = request()->get('parent');
        $level = request()->get('level');
        $model = new ToolsArea();

        switch ($level) {
            case 'province':
                $type = 1;
                break;
            case 'city':
                $type = 2;
                break;
            case 'region':
                $type = 3;
                break;
            case 'street':
                $type = 4;
                break;
            default:
                $type = 0;
        }
        $data = $model->where('level', $type);

        if ($name) {
            $data = $data->where('name', 'like', '%' . $name . '%');
        }
        if ($id) {
            $data = $data->where('name', $id);
        }else {
            $info = $model->where('name', $parent)->where('level', $type - 1)->first();
            $data = $data->where('parent_code', $parent ? $info['code'] : 0);
        }

        $data = $data->paginate($limit, ['name', 'name as id'])->toArray();
        return app_success('ok', [
            'data' => $data['data'],
            'total' => $data['total'],
        ]);
    }
}
