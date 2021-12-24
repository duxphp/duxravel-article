<?php

namespace Modules\Article\Service;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Kalnoy\Nestedset\QueryBuilder;
use Modules\Article\Model\Article;
use Modules\Article\Model\ArticleAttribute;
use Modules\Article\Model\ArticleClass;
use Modules\Article\Resource\ArticleCollection;
use Modules\Cms\Model\CmsTags;
use Modules\Cms\Model\CmsTagsHas;

/**
 * 标签扩展
 */
class Blade
{

    /**
     * 文章分类
     *
     * @param array $args
     *
     * @return Builder[]|Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection|\Kalnoy\Nestedset\Collection|QueryBuilder|QueryBuilder[]|ArticleClass|ArticleClass[]
     */
    public static function articleClass(array $args = [])
    {
        return \Modules\Article\Service\Article::class($args);
    }

    /**
     * 文章列表
     *
     * @param array $args
     *
     * @return LengthAwarePaginator|Builder[]|Collection
     */
    public static function article(array $args = [])
    {
        $data = \Modules\Article\Service\Article::list($args);

        $params = [
            'limit' => (int)$args['limit'] ?: 10,
            'keyword' => (string)$args['keyword'],
            'page' => (bool)$args['page'],
        ];

        if ($params['page']) {
            $data = $data->paginate($params['limit']);
        } else {
            $data = $data->limit($params['limit'])->get();
        }

        if ($params['keyword']) {
            $keyword = preg_replace('!\s+!', ' ', trim($params['keyword']));
            $keywords = explode(' ', $keyword);
            $data->map(function ($item) use ($keywords) {
                foreach ($keywords as $vo) {
                    $item->title = str_replace($vo, '<strong>' . $vo . '</strong>', $item->title);
                    $item->description = str_replace($vo, '<strong>' . $vo . '</strong>', $item->description);
                }
                return $item;
            });
        }

        $data->map(function ($item) {
            $item->view = $item->views->pv + $item->virtual_view;
            return $item;
        });

        return $data;
    }

    /**
     * 标签列表
     *
     * @param array $args
     *
     * @return Builder[]|Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection|ToolsTagsHas[]
     */
    public static function tags(array $args = [])
    {
        $data = \Modules\Article\Service\Article::tags($args);
        return $data->map(static function ($item) {
            return (object)[
                'name' => $item->tag->name,
                'count' => $item->tag->count,
                'view' => $item->tag->view
            ];
        });
    }
}

