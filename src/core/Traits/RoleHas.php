<?php

namespace Duxravel\Core\Traits;

use Duxravel\Core\Model\Role;

/**
 * Class RoleHas
 * @package Duxravel\Core\Traits
 */
trait RoleHas
{

    /**
     * @return mixed
     */
    public function roles()
    {
        return $this->morphToMany(Role::class, 'role', 'role_has', 'user_id', 'role_id');
    }

}
