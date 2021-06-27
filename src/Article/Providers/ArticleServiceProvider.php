<?php

namespace Modules\Article\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ArticleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        // 文章分类
        \Duxravel\Core\Util\Blade::loopMake('articleclass', \Modules\Article\Service\Blade::class, 'articleClass');
        // 文章列表
        \Duxravel\Core\Util\Blade::loopMake('article', \Modules\Article\Service\Blade::class, 'article', static function ($params) {
            $key = $params['assign'] ?: '$data';
            return <<<DATA
                if (method_exists($key, 'links')) {
                    \$pageData = $key;
                }
            DATA;
        });
        // 标签列表
        \Duxravel\Core\Util\Blade::loopMake('tags', \Modules\Article\Service\Blade::class, 'tags');

        // 注册数据库目录
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../../../database/migrations'));

    }
}
