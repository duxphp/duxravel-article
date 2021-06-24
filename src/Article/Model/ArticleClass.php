<?php

namespace Modules\Article\Model;

/**
 * Class ArticleClass
 * @package Modules\Article\Model
 */
class ArticleClass extends \Duxravel\Core\Model\Base
{
    use \Kalnoy\Nestedset\NodeTrait;

    /**
     * 树形分类
     * @return string[]
     */
    protected function getScopeAttributes()
    {
        return ['model_id'];
    }

    protected $table = 'article_class';

    protected $primaryKey = 'class_id';

    protected $fillable = ['name'];

    public function article()
    {
        return $this->belongsToMany(\Modules\Article\Model\Article::class, 'article_class_has', 'class_id', 'article_id');
    }

}
