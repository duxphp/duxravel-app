<?php

namespace Duxravel\Core\Listeners;

/**
 * 数据安装接口
 */
class InstallSeed
{

    /**
     * @param $event
     * @return array[]
     */
    public function handle($event)
    {
        return \Duxravel\Database\Seeders\DatabaseSeeder::class;
    }
}

