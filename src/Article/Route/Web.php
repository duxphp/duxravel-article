<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'   => 'article',
    'app' => '文章系统'
], function () {
    Route::group([
        'group' => '文章模块'
    ], function () {
        Route::get('list/{id?}', ['uses' => 'Modules\Article\Web\Article@index', 'desc' => '列表'])->name('web.article.list');
        Route::get('search', ['uses' => 'Modules\Article\Web\Article@search', 'desc' => '搜索'])->name('web.article.search');
        Route::get('tags/{tag}', ['uses' => 'Modules\Article\Web\Article@tags', 'desc' => '标签'])->name('web.article.tags');
        Route::get('info/{id}', ['uses' => 'Modules\Article\Web\Article@info', 'desc' => '详情'])->name('web.article.info');
    });
    // Generate Route Make
});

