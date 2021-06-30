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

        $this->meta($classInfo['name'], $classInfo['keyword'], $classInfo['description']);
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
        $classInfo = \Modules\Article\Model\ArticleClass::find($info->class[0]->class_id);
        if (!$classInfo) {
            app_error('栏目不存在', 404);
        }
        $info->viewsInc();
        $this->meta($info['title'], $info['keyword'] ?: $classInfo['keyword'], $info['description'] ?: $classInfo['description']);
        $this->assign('info', $info ?: collect());
        $this->assign('classInfo', $classInfo ?: collect());
        $tpl = $this->getParentValue($classInfo, 'tpl_content') ?: 'articleInfo';
        return $this->view($tpl);
    }

    public function search()
    {
        $keyword = request()->get('keyword');
        $classId = request()->get('class');
        $classInfo = \Modules\Article\Model\ArticleClass::find($classId);
        $this->meta($keyword . ($classInfo['name'] ? ' - ' . $classInfo['name'] : ''), $classInfo['keyword'] ?: $keyword, $classInfo['description']);
        $this->assign('keyword', $keyword);
        $this->assign('classInfo', $classInfo);
        return $this->view('articleSearch');
    }

    public function tags($tag)
    {
        $classId = request()->get('class');
        $classInfo = \Modules\Article\Model\ArticleClass::find($classId);
        $this->meta($tag . ($classInfo['name'] ? ' - ' . $classInfo['name'] : ''), $classInfo['keyword'] ?: $tag, $classInfo['description']);
        $this->assign('tag', $tag);
        $this->assign('classInfo', $classInfo);
        return $this->view('articleTags');
    }

    private function getParentValue($classInfo, $name)
    {
        $list = $classInfo->ancestorsAndSelf($classInfo->class_id);
        $modelInfo = \Modules\Article\Model\ArticleModel::find($classInfo->model_id);
        $value = '';
        foreach ($list as $item) {
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
