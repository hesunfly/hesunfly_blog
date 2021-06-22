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

use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;

/**
 * @return mixed|string
 * function:获取请求ip地址
 */
function get_client_ip()
{
    $request = Context::get(\Psr\Http\Message\ServerRequestInterface::class);
    $x_real_ip = $request->getHeaderLine('x-real-ip');
    if ($x_real_ip) {
        $ip = $x_real_ip;
        return filter_var($ip, FILTER_VALIDATE_IP) ?: '127.0.0.1';
    }
    if (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else {
        // for php-cli(phpunit etc.)
        $ip = defined('PHPUNIT_RUNNING') ? '127.0.0.1' : gethostbyname(gethostname());
    }
    return filter_var($ip, FILTER_VALIDATE_IP) ?: '127.0.0.1';
}

/**
 * @return bool
 * function: 判断是否为桌面端浏览器
 */
function isDesktop(): bool
{
    $detect = new Mobile_Detect();

    $request = ApplicationContext::getContainer()->get(\Hyperf\HttpServer\Contract\RequestInterface::class);
    $ua = $request->getHeaderLine('User-Agent');
    $headers = $request->getHeaders();
    return !$detect->isMobile($ua, $headers) && !$detect->isTablet($ua, $headers);
}

/**
 * @param int $code
 * @return \Psr\Http\Message\ResponseInterface
 * function:错误响应视图，目前支持404
 */
function abort($code = 404)
{
    switch ($code) {
        case 404:
            return ApplicationContext::getContainer()->get(
                \Hyperf\HttpServer\Contract\ResponseInterface::class
            )->redirect('/404');
    }
}

function saveSysOperationLog($module, $action, $opt_info)
{
    $request = ApplicationContext::getContainer()->get(\Hyperf\HttpServer\Request::class);
    \Hyperf\DbConnection\Db::table('operation_log')->insert(
        [
            'user_id' => Context::get('uid') ?? 0,
            'user_name' => Context::get('user_name') ?? '',
            'ip_address' => get_client_ip(),
            'request_info' => json_encode(
                [
                    'server_params' => $request->getServerParams(),
                    'request_body' => $request->getParsedBody(),
                ],
                JSON_UNESCAPED_UNICODE
            ),
            'module' => $module,
            'operation_time' => date('Y-m-d H:i:s', $request->getServerParams()['request_time']),
            'operation_action' => $action,
            'operation_system' => $request->getHeaderLine('user-agent'),
            'source_id' => Context::get('source_id') ?? 0,
            'operation_info' => $opt_info,
        ]
    );
}
