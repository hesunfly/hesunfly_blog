<?php

return [
    'indices' => [
        'mappings' => [
            'article' => [
                "properties" => [
                    "content" => [
                        "type" => "text",
                        "analyzer" => "ik_max_word",
                        "search_analyzer" => "ik_smart",
                    ],
                    "category" => [
                        "type" => "text",
                        "analyzer" => "ik_max_word",
                        "search_analyzer" => "ik_smart",
                    ],
                    "title" => [
                        "type" => "text",
                        "analyzer" => "ik_max_word",
                        "search_analyzer" => "ik_smart",
                    ],
                ],
            ],
        ],
    ],
];