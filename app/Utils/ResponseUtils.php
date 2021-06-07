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
namespace App\Utils;

use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Coroutine;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

function ajaxResponse($res = []): Psr7ResponseInterface
{
    $result = [
        'code' => empty($res['code']) ? 200 : $res['code'],
        'message' => empty($res['msg']) ? 'success' : $res['msg'],
        'request_time' => time(),
        'request_id' => uniqid('', false) . '_' . Coroutine::id(),
        'data' => empty($res['data']) ? [] : $res['data'],
    ];

    return ApplicationContext::getContainer()->get(ResponseInterface::class)->json($result);
}

