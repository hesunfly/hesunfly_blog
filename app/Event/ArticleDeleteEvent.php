<?php

namespace App\Event;

class ArticleDeleteEvent
{

    public $article_category_id;

    public function __construct($article_category_id)
    {
        $this->article_category_id = $article_category_id;
    }
}

