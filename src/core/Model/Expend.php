<?php

namespace Duxravel\Core\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Duxravel\Core\Util\Tree;
use Modules\System\Service\Form;

/**
 * Trait Expend
 * @package Duxravel\Core\Model
 */
trait Expend
{
    /**
     * 模型关联标志
     * @var string
     */
    protected string $hasName = '';


    /**
     * 获取当前时间
     *
     * @return int
     */
    public function freshTimestamp(): int
    {
        return time();
    }

    /**
     * 避免转换时间戳为时间字符串
     * @param mixed $value
     * @return mixed|string|null
     */
    public function fromDateTime($value)
    {
        return $value;
    }

}
