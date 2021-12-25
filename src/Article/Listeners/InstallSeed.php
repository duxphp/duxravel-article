<?php

namespace Modules\Article\Listeners;

/**
 * 数据安装接口
 */
class InstallSeed
{

    /**
     * @param $event
     *
     * @return string
     */
    public function handle($event)
    {
        return \Modules\Article\Seeders\DatabaseSeeder::class;
    }
}
