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


}
