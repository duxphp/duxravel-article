<?php

namespace Modules\Article\Service;


use Modules\Article\Model\ArticleAttribute;
use Modules\Article\Model\ArticleClass;
use Modules\Cms\Model\CmsTagsHas;

/**
 * 文章服务
 */
class Article
{

    public static function class()
    {
        $params = [
            'id' => $args['id'] ?: 0,
            'limit' => $args['limit'] ?: null,
            'model' => (string)$args['model'] ?: 0,
            'sub' => (int)$args['sub'],
            'parent' => (int)$args['parent'],
            'siblings' => (int)$args['siblings'],
        ];
        $data = new ArticleClass();

        if ($params['id']) {
            if ($params['model']) {
                $data = $data->where(['model_id' => $params['model']]);
            }
            if (is_array($params['id'])) {
                return $data->limit($params['limit'])->whereIn('class_id', $params['id'])->get();
            } else {
                return $data->limit($params['limit'])->where('class_id', $params['id'])->get();
            }
        }

        if ($params['model']) {
            $data = $data->scoped(['model_id' => $params['model']]);
        }

        if ($params['sub']) {
            $class = $data->find($params['sub']);
        }
        if ($params['siblings']) {
            $class = $data->find($params['siblings']);
        }
        if ($params['parent']) {
            $class = $data->find($params['parent']);
        }

        if (!$params['model'] && isset($class)) {
            $data = $data->scoped(['model_id' => $class->model_id]);
        }

        if ($params['siblings']) {
            return $data->defaultOrder()->descendantsOf($class->parent_id)->toTree();
        }

        if ($params['parent']) {
            return $data->defaultOrder()->ancestorsAndSelf($class->class_id);
        }

        if ($params['sub']) {
            return $data->defaultOrder()->descendantsOf($class->class_id)->toTree();
        }

        return $data->defaultOrder()->get()->toTree()->take($params['limit']);
    }

    public static function list($args)
    {
        $params = [
            'sub' => $args['sub'] ?: 0,
            'class' => $args['class'] ?: 0,
            'offset' => (int)$args['offset'] ?: 0,
            'model' => (int)$args['model'] ?: 0,
            'image' => (bool)$args['image'],
            'attr' => (int)$args['attr'],
            'keyword' => (string)$args['keyword'],
            'tag' => (string)$args['tag'],
            'sort' => (array)$args['sort'],
            'formWhere' => (array)$args['formWhere'],
        ];

        $data = new \Modules\Article\Model\Article();
        $data = $data->with('class')->with('views')
            ->where('status', 1)
            ->where('release_at', '<=', date('Y-m-d H:i:s'));

        if ($params['model']) {
            $data = $data->where('model_id', $params['model']);
        }

        if (isset($args['image']) && $params['image']) {
            $data = $data->where('image', '<>', null);
        }

        if (isset($args['image']) && !$params['image']) {
            $data = $data->where('image', null);
        }

        if (isset($args['attr'])) {
            $data = $data->with('attribute');
            $data = $data->whereHas('attribute', static function ($query) use ($params) {
                $query->where((new \Modules\Article\Model\ArticleAttribute())->getTable() . '.attr_id', $params['attr']);
            });
        }

        if (isset($args['offset'])) {
            $data = $data->offset($params['offset']);
        }

        if ($params['sub']) {
            $classInfo = ArticleClass::find($params['sub']);
            $ids = $classInfo->scoped(['model_id' => $classInfo['model_id']])->descendantsAndSelf($params['sub'])->pluck('class_id');
            $data->whereHas('class', function ($query) use ($ids) {
                $query->whereIn((new ArticleClass())->getTable() . '.class_id', $ids);
            });
        }

        if ($params['class']) {
            $data->whereHas('class', function ($query) use ($params) {
                if (is_array($params['class'])) {
                    $query->whereIn((new ArticleClass())->getTable() . '.class_id', $params['class']);
                } else {
                    $query->where((new ArticleClass())->getTable() . '.class_id', $params['class']);
                }
            });
        }

        if ($params['formWhere'] && is_array($params['formWhere'])) {
            $data = $data->with('form');
            $data = $data->whereHas('form', static function ($query) use ($params) {
                $formTable = (new \Duxravel\Core\Model\FormData())->getTable();
                foreach ($params['formWhere'] as $key => $vo) {
                    $query->where($formTable . '.data->' . $key, $vo);
                }
            });

        }

        if (isset($args['keyword'])) {
            $data = $data->whereRaw("MATCH(title,content) AGAINST(?  IN BOOLEAN MODE)", [$params['keyword']]);
        }

        if (isset($args['tag'])) {
            $data = $data->with('tags');
            $data = $data->whereHas('tags', static function ($query) use ($args) {
                $query->where((new \Modules\Cms\Model\CmsTags())->getTable() . '.name', $args['tag']);
            });
        }

        if ($params['sort']) {
            $sorts = $params['sort'];
            if ($params[0]) {
                $sorts = [$params['sort'][0] => $params['sort'][1]];
            }
            foreach ($sorts as $key => $vo) {
                if ($key === 'attr') {
                    $cloneData = clone $data;
                    $cloneData = $cloneData->with('attribute');
                    $cloneData->whereHas('attribute', function ($query) use ($vo) {
                        $query->where((new ArticleAttribute())->getTable() . '.attr_id', $vo);
                    });
                    foreach ($sorts as $v) {
                        if ($v === 'time') {
                            $cloneData = $cloneData->orderBy('release_at', $v);
                        }
                        if ($v === 'sort') {
                            $cloneData = $cloneData->orderBy('sort', $v);
                        }
                        if ($v === 'view') {
                            $cloneData = $cloneData->orderByWith('views', 'pv', $v);
                        }
                        if ($v === 'id') {
                            $cloneData = $cloneData->orderBy('article_id', $v);
                        }
                    }
                    $ids = $cloneData->pluck('article_id')->join(',');
                    if ($ids) {
                        $data = $data->orderByRaw(\DB::raw("`article_id` in ($ids) desc"));
                    }
                }
                if ($key === 'time') {
                    $data = $data->orderBy('release_at', $vo);
                }
                if ($key === 'sort') {
                    $data = $data->orderBy('sort', $vo);
                }
                if ($key === 'view') {
                    $data = $data->orderByWith('views', 'pv', $vo);
                }
                if ($key === 'id') {
                    $data = $data->orderBy('article_id', $vo);
                }
            }
        }

        return $data;
    }


    public static function tags($args)
    {
        $params = [
            'limit' => $args['limit'] ?: 10,
            'sort' => (array)$args['sort']
        ];
        $data = new CmsTagsHas();
        $data = $data->where('has_type', \Modules\Article\Model\Article::class);
        $data = $data->with('tag');
        $data = $data->limit($params['limit']);
        if ($params['sort']) {
            $sorts = $params['sort'];
            if (!$params[0]) {
                $sorts = [$params['sort'][0] => $params['sort'][1]];
            }
            foreach ($sorts as $key => $vo) {
                if ($key === 'count') {
                    $data = $data->orderByWith('tag', 'count', $vo);
                }
                if ($key === 'view') {
                    $data = $data->orderBy('view', $vo);
                }
            }
        }
        return $data->get();
    }
}

