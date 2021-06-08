<?php

namespace App\Listener;

use App\Event\ArticleDeleteEvent;
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
        $article = $event->article;

        var_dump($article);
    }
}