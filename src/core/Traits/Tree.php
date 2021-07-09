<?php

namespace Duxravel\Core\Traits;

/**
 * Class Tree
 * @package Duxravel\Core\Traits
 */
trait Tree
{
    /**
     * 获取下级
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(get_class($this), 'parent_id');
    }

    /**
     * 获取所有下级
     * @return mixed
     */
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    /**
     * 获取上级
     * @return mixed
     */
    public function parent()
    {
        return $this->belongsTo(get_class($this), 'parent_id');
    }

    /**
     * 获取所有上级
     * @return mixed
     */
    public function allParent()
    {
        return $this->parent()->with('allParent');
    }

}
