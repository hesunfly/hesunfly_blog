<?php

namespace App\Listener;

use App\Event\ArticleCreateEvent;
use Hyperf\Event\Contract\ListenerInterface;

class ArticleCreateListener implements ListenerInterface
{

    public function listen(): array
    {
        return [
            ArticleCreateEvent::class,
        ];
    }

    public function process(object $event)
    {
        $article = $event->article;

        var_dump($article);
    }
}