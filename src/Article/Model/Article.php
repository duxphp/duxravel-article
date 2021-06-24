<?php

namespace Modules\Article\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Article
 * @package Modules\Article\Model
 */
class Article extends \Duxravel\Core\Model\Base
{
    use SoftDeletes;
    use \Conner\Tagging\Taggable;
    use \Duxravel\Core\Traits\Form;
    use \Duxravel\Core\Traits\Visitor;

    protected array $softCascade = ['profiles'];

    protected $table = 'article';

    protected $primaryKey = 'article_id';

    protected string $hasName = 'article';

    protected static function boot()
    {
        parent::boot();
        static::deleting(function($model) {
            $model->class()->detach();
            $model->attribute()->detach();
            $model->untag();
            $model->viewsDel();
            $model->formDel();
        });
    }

    public function class(): BelongsToMany
    {
        return $this->belongsToMany(\Modules\Article\Model\ArticleClass::class, 'article_class_has', 'article_id', 'class_id');
    }

    public function attribute(): BelongsToMany
    {
        return $this->belongsToMany(\Modules\Article\Model\ArticleAttribute::class, 'article_attribute_has', 'article_id', 'attr_id');
    }

}
