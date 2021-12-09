<?php

namespace Modules\Article\Listeners;

use Duxravel\Core\Util\Tree;
use Modules\Article\Model\Article;
use Modules\Article\Model\ArticleClass;

/**
 * 数据安装接口
 */
class MenuUrl
{

    /**
     * @param $event
     * @return array[]
     */
    public function handle($event)
    {
        return [
            [
                'name' => '文章分类',
                'model' => ArticleClass::class,
                'maps' => [
                    'name' => 'name',
                    'webUrl' => static function ($item) {
                        return route('web.article.list', ['id' => $item['class_id']], false);
                    }
                ],
                'callback' => static function ($data) {
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
                    'webUrl' => static function ($item) {
                        return route('web.article.info', ['id' => $item['article_id']], false);
                    }
                ]
            ]
        ];
    }
}
