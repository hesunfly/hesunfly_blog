<?php

namespace App\Listener;

use App\Event\ArticlePublishEvent;
use Hyperf\Event\Contract\ListenerInterface;

class ArticlePublishListener implements ListenerInterface
{

    public function listen(): array
    {
        return [
            ArticlePublishEvent::class,
        ];
    }

    public function process(object $event)
    {
        //生成二维码，发送订阅邮件
        $article = $event->article;
    }
}