<?php

namespace App\Event;

class ArticleDeleteEvent
{

    public $article;

    public function __construct($article)
    {
        $this->article = $article;
    }
}

