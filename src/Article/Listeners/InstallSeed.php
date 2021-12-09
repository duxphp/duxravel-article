<?php

namespace Modules\Article\Listeners;

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
        return \Duxravel\Article\Seeders\DatabaseSeeder::class;
    }
}
