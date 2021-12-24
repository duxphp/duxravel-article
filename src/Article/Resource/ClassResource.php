<?php

namespace Modules\Article\Resource;

use Duxravel\Core\Resource\BaseResource;

class ClassResource extends BaseResource
{

    public static function collection($resource)
    {
        return tap(new ClassCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request): array
    {
        return [
            'class_id' => $this->class_id,
            'name' => $this->name,
            'subname' => $this->subname,
            'image' => $this->image,
            'keyword' => $this->keyword,
            'description' => $this->description,
            'content' => $this->content,
        ];
    }
}
