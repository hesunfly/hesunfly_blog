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

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\View\RenderInterface;

/**
 * @Controller(prefix="auth")
 * Class AuthController
 */
class AuthController extends Controller
{
    /**
     * @GetMapping(path="login")
     * function:
     * @param RenderInterface $render
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login(RenderInterface $render): \Psr\Http\Message\ResponseInterface
    {
        return $render->render('admin.login');
    }

    /**
     * @PostMapping(path="doLogin")
     * function:
     */
    public function doLogin()
    {

    }
}
