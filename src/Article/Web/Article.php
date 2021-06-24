<?php

namespace Modules\Article\Web;

use Modules\Article\Resource\ArticleCollection;
use Duxravel\Core\Web\Base;

class Article extends Base
{
    public function index($id)
    {
        $classInfo = \Modules\Article\Model\ArticleClass::find($id);
        $this->assign('classInfo', $classInfo ?: collect());
        return $this->view('articleList');
    }

    public function info($id)
    {
        $info = \Modules\Article\Model\Article::find($id);
        $this->assign('articleInfo', $info ?: collect());
        return $this->view('articleInfo');
    }

    public function search()
    {
        $keyword = request()->get('keyword');
        $classId = request()->get('class');
        $classInfo = \Modules\Article\Model\ArticleClass::find($classId);
        $this->assign('keyword', $keyword);
        $this->assign('classInfo', $classInfo);
        return $this->view('articleSearch');
    }

    public function tags($tag)
    {
        $classId = request()->get('class');
        $classInfo = \Modules\Article\Model\ArticleClass::find($classId);
        $this->assign('tag', $tag);
        $this->assign('classInfo', $classInfo);
        return $this->view('articleTags');
    }
}
