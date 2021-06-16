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
namespace App\Controller\Web\Admin;

use App\Model\User;
use App\Request\AuthRequest;
use Hyperf\HttpMessage\Exception\UnauthorizedHttpException;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use HyperfExt\Hashing\Hash;
use Qbhy\HyperfAuth\Annotation\Auth;

use function Hyperf\ViewEngine\view;

/**
 * @Controller(prefix="auth")
 * Class AuthController
 */
class AuthController extends BaseController
{
    /**
     * @GetMapping(path="login")
     * function:
     */
    public function login(ResponseInterface $response)
    {
        if ($this->auth->check()) {
            return $response->redirect('/admin');
        }
        return view('admin.login');
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
     * @Auth
     * @GetMapping(path="user/edit")
     * function:
     */
    public function edit()
    {
        return view('admin.user', ['user' => $this->auth->user()]);
    }

    /**
     * @PutMapping(path="user/save")
     * function:
     */
    public function save(RequestInterface $request, ResponseInterface $response)
    {
        $params = $request->all();

        if ($params['password']) {
            $params['password'] = Hash::make($params['password']);
        }
        User::query()->first()->update($params);

        return $response->raw('success');
    }

    /**
     * @Auth
     * @DeleteMapping(path="logout")
     * function:
     */
    public function logout(ResponseInterface $response)
    {
        $this->auth->logout();

        return $response->raw('success')->withStatus(204);
    }

}
