<?php

namespace App\Event;

class ArticlePublishEvent
{

    public $article;

    public function __construct($article)
    {
        $this->article = $article;
    }
}

