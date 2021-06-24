<?php

namespace Modules\Article\Resource;

use Duxravel\Core\Resource\BaseResource;

class ArticleResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content
        ];
    }
}
