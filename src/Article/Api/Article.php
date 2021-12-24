<?php

namespace Modules\Article\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Article\Resource\ClassCollection;
use Modules\Article\Resource\ArticleCollection;
use \Modules\Article\Resource\ArticleResource;
use Duxravel\Core\Api\Api;
use Modules\Article\Resource\TagsCollection;

class Article extends Api
{

    public function class(\Illuminate\Http\Request $request)
    {
        $article = \Modules\Article\Service\Article::class($request->all());
        $res = new ClassCollection($article);
        return $this->success($res->hide(['content']));
    }

    public function list(\Illuminate\Http\Request $request)
    {
        $article = \Modules\Article\Service\Article::list($request->all());
        $res = new ArticleCollection($article->paginate());
        return $this->success($res->hide(['content']));
    }

    public function info($id)
    {
        $info = \Modules\Article\Model\Article::find($id);
        if (!$info) {
            return $this->error('文章不存在');
        }
        $res = new ArticleResource($info);
        return $this->success($res);
    }

    public function tags(\Illuminate\Http\Request $request)
    {
        $res = new TagsCollection(\Modules\Article\Service\Article::tags($request->all()));
        return $this->success($res);
    }
}
