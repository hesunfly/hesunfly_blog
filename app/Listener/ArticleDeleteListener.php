<?php

namespace App\Listener;

use App\Event\ArticleDeleteEvent;
use App\Model\Category;
use Hyperf\Event\Contract\ListenerInterface;

class ArticleDeleteListener implements ListenerInterface
{

    public function listen(): array
    {
        return [
            ArticleDeleteEvent::class,
        ];
    }

    public function process(object $event)
    {
        $id = $event->article_category_id;
        Category::query()->where('id', $id)->decrement('count');
    }
}