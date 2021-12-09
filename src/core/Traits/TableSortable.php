<?php

namespace Duxravel\Core\Traits;

/**
 * Class TableSortable
 * @package Duxravel\Core\Traits
 */
trait TableSortable
{

    /**
     *
     * 排序方法
     * 引入该方法并注册排序路由
     */
    public function sortable()
    {
        $id = request()->input('id');
        $parent = request()->input('parent');
        $before = request()->input('before');
        $after = request()->input('after');
        $info = $this->model::find($id);

        if ($before) {
            $node = $this->model::find($before);
            $info->insertAfterNode($node);
        } else if ($after) {
            $node = $this->model::find($after);
            $info->insertBeforeNode($node);

        } else if ($parent) {
            $node = $this->model::find($parent);
            $node->prependNode($info);
        } else {
            $info->saveAsRoot();
        }
        return app_success('ok');
    }

}
