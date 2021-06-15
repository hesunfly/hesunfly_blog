<?php

namespace App\Event;

class ArticleShowEvent
{

    public $article;

    public function __construct($article)
    {
        $this->article = $article;
    }
}

