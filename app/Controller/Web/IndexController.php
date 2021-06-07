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
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\View\RenderInterface;
use Qbhy\HyperfAuth\AuthMiddleware;

/**
 * @Controller(prefix="admin")
 * @Middleware(AuthMiddleware::class)
 * Class IndexController
 */
class IndexController extends BaseController
{
    /**
     * @GetMapping(path="")
     * function:
     */
    public function index(RenderInterface $render)
    {
        return $render->render('admin.index');
    }
}
