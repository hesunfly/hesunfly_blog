<?php

namespace App\Resource;

use Hyperf\Resource\Json\JsonResource;

class ArticleShow extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->html_content,
            'view_count' => $this->view_count,
            'category' => $this->category->title,
            'qr_path' => isDesktop() ? $this->qr_path : '',
            'publish_at' => $this->publish_at ?: '未发布',
        ];
    }
}
