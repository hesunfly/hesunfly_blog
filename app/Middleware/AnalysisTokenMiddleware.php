<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Context;
use Phper666\JwtAuth\Jwt;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AnalysisTokenMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var Jwt
     */
    protected $jwt;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /*$token = $request->getHeaderLine('Authorization');
        $deviceType = $request->getHeaderLine('DeviceType');
        if ($token) {
            $token_info = $this->jwt->getParserData();
            Context::getOrSet('uid', $token_info['uid']);
            if (isset($token_info['role'])) {
                Context::getOrSet('role', $token_info['role']);
            }
        }
        if ($deviceType) {
            Context::getOrSet('device_type', $deviceType);
        }

        return $handler->handle($request);*/
    }
}
