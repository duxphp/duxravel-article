<?php

namespace Modules\Article\Resource;

use Duxravel\Core\Resource\BaseResource;

class ArticleResource extends BaseResource
{

    public function toArray($request): array
    {
        return [
            'article_id' => $this->article_id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'keyword' => $this->keyword,
            'description' => $this->description,
            'image' => $this->image,
            'auth' => $this->auth,
            'source' => $this->source,
            'content' => $this->content,
            'view' => $this->views->pv + $this->virtual_view,
            'class' => ArticleClassResource::collection($this->class)->hide(['content']),
            'tags' => $this->tags->transform(function ($item) {
                return $item->only(['name', 'tag_id', 'count', 'value']);
            }),
            'attr' => $this->attribute->pluck('name')
        ];
    }
}
