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
namespace App\Middleware;

use App\Model\VisitRecord;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VisitRecordMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri_obj = ApplicationContext::getContainer()->get(RequestInterface::class)->getUri();
        $uri = $uri_obj->getPath() . $uri_obj->getQuery() . $uri_obj->getFragment();
        $ip = get_client_ip();
        go(
            function () use ($uri, $ip) {
                VisitRecord::query()->create(
                    [
                        'ip' => $ip,
                        'uri' => urldecode($uri),
                    ]
                );
            }
        );

        return $handler->handle($request);
    }
}
