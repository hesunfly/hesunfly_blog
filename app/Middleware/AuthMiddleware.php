<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\Contract\ConfigInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Phper666\JwtAuth\Exception\TokenValidException;
use Phper666\JwtAuth\Jwt;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var HttpResponse
     */
    protected $response;

    protected $prefix = 'Bearer';

    protected $jwt;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(HttpResponse $response, Jwt $jwt, ContainerInterface $container)
    {
        $this->response = $response;
        $this->jwt = $jwt;
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        $whitelist = $this->container->get(ConfigInterface::class)->get('jwt.whitelist');
        if (in_array($path, $whitelist)) {
            return $handler->handle($request);
        }
        $isValidToken = false;
        // 根据具体业务判断逻辑走向，这里假设用户携带的token有效
        $token = $request->getHeader('Authorization')[0] ?? '';
        if (strlen($token) > 0) {
            $token = ucfirst($token);
            $arr = explode($this->prefix . ' ', $token);
            $token = $arr[1] ?? '';
            if (strlen($token) > 0 && $this->jwt->checkToken()) {
                //校验该用户是否需要重新登录
                $deviceType     = $request->getHeaderLine('DeviceType');
                $token_info = $this->jwt->getParserData();
                $has = getRedis()->sIsMember('invalid_user_logout_id_' . $deviceType, $token_info['uid']);
                if (!$has) {
                    $isValidToken = true;
                }
            }
        }
        if ($isValidToken) {
            return $handler->handle($request);
        }
        echo 'path:' . $path;
        throw new TokenValidException('Token authentication does not pass', 401);
    }
}
