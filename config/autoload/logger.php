<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'default' => [
        'handler' => [
                'class' => \Monolog\Handler\StreamHandler::class,
                'constructor' => [
                    'stream' => 'php://stdout',
                    'level' => \Monolog\Logger::INFO,
                ],
        ],
        'formatter' => [
            'class' => \Monolog\Formatter\LineFormatter::class,
            'constructor' => [
                'format' => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
                'allowInlineLineBreaks' => true,
                'includeStacktraces' => true,
            ],
        ]
    ],
];
