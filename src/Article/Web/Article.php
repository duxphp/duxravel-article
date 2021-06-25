<?php

namespace Modules\Article\Web;

use Modules\Article\Resource\ArticleCollection;
use Duxravel\Core\Web\Base;

class Article extends Base
{
    public function index($id)
    {
        $classInfo = \Modules\Article\Model\ArticleClass::find($id);
        if (!$classInfo) {
            app_error('栏目不存在', 404);
        }

        $this->assign('classInfo', $classInfo ?: collect());
        $tpl = $this->getParentValue($classInfo, 'tpl_class') ?: 'articleList';
        return $this->view($tpl);
    }

    public function info($id)
    {
        $info = \Modules\Article\Model\Article::find($id);
        if (!$info) {
            app_error('新闻不存在', 404);
        }
        $classInfo = \Modules\Article\Model\ArticleClass::find($id);
        if (!$classInfo) {
            app_error('栏目不存在', 404);
        }
        $this->assign('articleInfo', $info ?: collect());
        $this->assign('classInfo', $classInfo ?: collect());
        $tpl = $this->getParentValue($classInfo, 'tpl_content') ?: 'articleList';
        return $this->view($tpl);
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

    private function getParentValue($classInfo, $name)
    {
        $classInfo->ancestorsAndSelf($classInfo->class_id);
        $modelInfo = \Modules\Article\Model\ArticleModel::find($classInfo->model_id);
        $value = '';
        foreach ($classInfo as $item) {
            if ($item->{$name}) {
                $value = $item->{$name};
                break;
            }
        }
        if (!$value) {
            $value = $modelInfo->{$name};
        }
        return $value;
    }
}
