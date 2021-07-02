<?php

namespace App\Resource;

use Hyperf\Resource\Json\ResourceCollection;

class ArticleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $res = $this->collection->toArray();

        foreach ($res as &$item) {
             $item['category'] = $item['category']['title'];
             unset($item['category_id']);
        }
        unset($item);

        return [
            'data' => $res,
        ];
    }
}
