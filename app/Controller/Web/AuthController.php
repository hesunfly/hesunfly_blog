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
namespace App\Controller\Web;

use App\Model\User;
use App\Request\AuthRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Exception\UnauthorizedHttpException;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\View\RenderInterface;
use HyperfExt\Hashing\Hash;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Qbhy\HyperfAuth\Annotation\Auth;
use Qbhy\HyperfAuth\AuthManager;

/**
 * @Controller(prefix="auth")
 * Class AuthController
 */
class AuthController extends Controller
{
    /**
     * @Inject
     * @var AuthManager
     */
    private $auth;

    /**
     * @GetMapping(path="login")
     * function:
     */
    public function login(RenderInterface $render): \Psr\Http\Message\ResponseInterface
    {
        return $render->render('admin.login');
    }

    /**
     * @PostMapping(path="doLogin")
     * function:
     */
    public function doLogin(AuthRequest $request, ResponseInterface $response)
    {
        $params = $request->all();

        $user = User::query()->where('email', $params['name'])->first();

        if (! $user) {
            throw new UnauthorizedHttpException('账户不存在！', -1);
        }

        if (! Hash::check($params['password'], $user['password'])) {
            throw new UnauthorizedHttpException('账号或密码错误！', -1);
        }

        $this->auth->login($user);

        return $response->raw('success');
    }

    /**
     * @Auth()
     * @GetMapping(path="me")
     * function:
     */
    public function user(ResponseInterface $response)
    {
        return $response->json([$this->auth->user()]);
    }
}
