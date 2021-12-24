<?php

use Illuminate\Support\Facades\Route;


Route::get('article', ['uses' => 'Modules\Article\Api\Article@list', 'desc' => '文章列表'])->name('api.article.article.list');
Route::get('article/{id}', ['uses' => 'Modules\Article\Api\Article@info', 'desc' => '文章详情'])->name('api.article.article.info');



Route::get('articleClass', ['uses' => 'Modules\Article\Api\Article@class', 'desc' => '文章分类'])->name('api.article.article.class');
Route::get('articleTags', ['uses' => 'Modules\Article\Api\Article@tags', 'desc' => '文章标签'])->name('api.article.article.tags');

