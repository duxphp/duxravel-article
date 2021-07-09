<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'   => 'article',
    'auth_app' => '文章系统'
], function () {


    Route::group([
        'auth_group' => '文章分类'
    ], function () {
        Route::manage(\Modules\Article\Admin\ArticleClass::class)->prefix('articleClass-{model}')->only(['index', 'page', 'save', 'del', 'status', 'sort'])->make();
    });

    Route::group([
        'auth_group' => '文章管理'
    ], function () {
        Route::manage(\Modules\Article\Admin\Article::class)->prefix('article-{model}')->make();
    });

    Route::group([
        'auth_group' => '文章模型'
    ], function () {
        Route::manage(\Modules\Article\Admin\ArticleModel::class)->only(['index', 'page', 'save', 'del'])->make();
    });
    Route::group([
        'auth_group' => '内容属性'
    ], function () {
        Route::manage(\Modules\Article\Admin\Attribute::class)->make();
    });
    // Generate Route Make
});

