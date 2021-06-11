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

use App\Model\Article;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Qbhy\HyperfAuth\AuthMiddleware;

use function Hyperf\ViewEngine\view;

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
    public function index()
    {
        $article_count = Article::query()
            ->where('status', 1)->count();

        return view('admin.index', ['article_count' => $article_count]);
    }
}
