<?php

use \Duxravel\Core\Facades\Menu;


Menu::add('article', [
    'name'  => '文章',
    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>',
    'order' => 10,
], function () {
    Menu::group([
        'name' => '内容管理',
        'order' => 0,
    ], function () {
        $model = app(\Modules\Article\Model\ArticleModel::class)->get();
        $menuList = $model->map(function ($item) {
            Menu::link($item['name'] . '管理', 'admin.article.article', ['model' => $item['model_id']]);
        });
    });

    Menu::group([
        'name' => '内容设置',
        'order' => 200,
    ], function () {
        Menu::link('模型管理', 'admin.article.articleModel');
        Menu::link('内容属性', 'admin.article.attribute');
    });

});
