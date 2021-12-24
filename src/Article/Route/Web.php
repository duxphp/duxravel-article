<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'app' => '文章模块'
], function () {
    Route::get('article/{id?}', ['uses' => 'Modules\Article\Web\Article@index', 'desc' => '文章列表'])->name('web.article.list');
    Route::get('article-search', ['uses' => 'Modules\Article\Web\Article@search', 'desc' => '文章搜索'])->name('web.article.search');
    Route::get('article-tags/{tag}', ['uses' => 'Modules\Article\Web\Article@tags', 'desc' => '文章标签'])->name('web.article.tags');
    Route::get('article-info/{id}', ['uses' => 'Modules\Article\Web\Article@info', 'desc' => '文章详情'])->name('web.article.info');
    // Generate Route Make
});

