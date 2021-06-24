<?php

namespace Modules\Article\Model;

/**
 * class ArticleAttribute
 * @package Modules\Article\Model
 */
class ArticleAttribute extends \Duxravel\Core\Model\Base
{

    protected $table = 'article_attribute';

    protected $primaryKey = 'attr_id';

    public function article()
    {
        return $this->belongsToMany(\Modules\Article\Model\Article::class, 'article_attribute_has', 'attr_id', 'article_id');
    }
}
