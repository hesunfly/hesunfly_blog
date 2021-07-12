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
    'handler' => [
        'http' => [
            \Hyperf\Validation\ValidationExceptionHandler::class,
            \App\Exception\Handler\AuthExceptionHandler::class,
            \App\Exception\Handler\DbSaveExceptionHandler::class,
            \App\Exception\Handler\DbQueryExceptionHandler::class,
            \App\Exception\Handler\ModelNotFoundExceptionHandler::class,
            \Hyperf\ExceptionHandler\Handler\WhoopsExceptionHandler::class,
            \Hyperf\ExceptionHandler\Listener\ErrorExceptionHandler::class,
            App\Exception\Handler\AppExceptionHandler::class,
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,
        ],
    ],
];
