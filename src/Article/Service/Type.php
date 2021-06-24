<?php

namespace Modules\Article\Service;

use Modules\Article\Model\Article;
use Modules\Article\Model\ArticleClass;
use Duxravel\Core\Util\Tree;

/**
 * 类型接口
 */
class Type
{
    public function getMenuUrl(): array
    {
        return [
            [
                'name' => '文章分类',
                'model' => ArticleClass::class,
                'maps' => [
                    'name' => 'name',
                    'webUrl' => static function($item) {
                        return route('web.article.list', ['id' => $item['class_id']], false);
                    }
                ],
                'callback' => static function($data) {
                    $data = collect(Tree::arr2table($data, 'class_id', 'parent_id'));
                    return $data->map(function ($items) {
                        $items['name'] = $items['spl'] . $items['name'];
                        return $items;
                    });
                },
                'limit' => 100
            ],
            [
                'name' => '文章详情',
                'model' => Article::class,
                'maps' => [
                    'name' => 'title',
                    'webUrl' => static function($item) {
                        return route('web.article.info', ['id' => $item['article_id']], false);
                    }
                ]
            ]
        ];
    }

    public function getQuickSearch(): array
    {
        $data = [];
        $modelList = \Modules\Article\Model\ArticleModel::all();
        foreach ($modelList as $vo) {
            $data[] = [
                'name' => $vo->name,
                'url' => route("admin.article.article.data", ['model' => $vo->model_id]),
                'map' => [
                    'title' => 'title',
                    'image' => 'image',
                    'desc' => 'desc',
                    'url' => 'manage_url'
                ]
            ];
        }
        return $data;
    }
}

