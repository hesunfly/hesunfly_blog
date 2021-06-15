<?php

namespace App\Event;

class ArticleCreateEvent
{

    public $article;

    public function __construct($article)
    {
        $this->article = $article;
    }
}

