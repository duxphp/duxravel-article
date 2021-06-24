<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'   => 'article',
    'auth_app' => '文章系统'
], function () {
    Route::group([
        'auth_group' => '文章模块'
    ], function () {
        Route::post('article', ['uses' => 'Modules\Article\Api\Article@index', 'desc' => '列表'])->name('api.article.article');
    });
    // Generate Route Make
});

