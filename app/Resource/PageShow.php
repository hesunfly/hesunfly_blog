<?php

namespace App\Resource;

use Hyperf\Resource\Json\JsonResource;

class PageShow extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->html_content,
            'created_at' => $this->created_at->toDateString(),
        ];
    }
}
