<?php

namespace App\Listener;

use App\Event\ArticleShowEvent;
use App\Model\Article;
use Carbon\Carbon;
use Hyperf\Cache\Cache;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Utils\ApplicationContext;

class ArticleShowListener implements ListenerInterface
{

    public function listen(): array
    {
        return [
            ArticleShowEvent::class,
        ];
    }

    public function process(object $event)
    {
        $article = $event->article;
        $key = 'view_article_' . $article->id . ':' . $article->visit_ip;

        $cache = ApplicationContext::getContainer()->get(Cache::class);

        if (! $cache->has($key)) {
            $cache->set($key, 'view', Carbon::now()->addHours(12));

            Article::query()->where('id', $article->id)->increment('view_count');
        }
    }
}