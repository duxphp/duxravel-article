<?php

namespace Modules\Article\Providers;

use Duxravel\Core\Util\Menu;
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
        // 注册公共路由
        $router->group(['prefix' => 'admin', 'public' => true, 'auth_has' => 'admin', 'middleware' => ['web']], function () {
            $this->loadRoutesFrom(realpath(__DIR__ . '/../Route/Admin.php'));
        });
        $router->group(['prefix' => 'admin', 'auth_has' => 'admin', 'middleware' => ['auth.manage']], function () {
            $this->loadRoutesFrom(realpath(__DIR__ . '/../Route/AuthAdmin.php'));
        });

        $router->group(['middleware' => ['api']], function () {
            $this->loadRoutesFrom(realpath(__DIR__ . '/../Route/Api.php'));
        });

        if (\Request::is('admin/*')) {
            // 注册菜单
            app(\Duxravel\Core\Util\Menu::class)->add('admin', function () {
                return app(\Modules\Article\Service\Menu::class)->getAdminMenu();
            });
            // 注册钩子接口
            app(\Duxravel\Core\Util\Hook::class)->add('service', 'type', 'getMenuUrl', function () {
                return app(\Modules\Article\Service\Type::class)->getMenuUrl();
            });
            app(\Duxravel\Core\Util\Hook::class)->add('service', 'type', 'getQuickSearch', function () {
                return app(\Modules\Article\Service\Type::class)->getQuickSearch();
            });
        }


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
