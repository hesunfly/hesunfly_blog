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

use App\Model\Category;
use App\Request\CategoryRequest;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\View\RenderInterface;
use Qbhy\HyperfAuth\AuthMiddleware;

/**
 * @Controller(prefix="admin/category")
 * @Middleware(AuthMiddleware::class)
 * Class IndexController
 */
class CategoryController extends BaseController
{
    /**
     * @GetMapping(path="")
     * function:
     */
    public function index(RenderInterface $render)
    {
        $category = Category::query()->select('id', 'title', 'count', 'created_at')->get();
        return $render->render('admin.category.index', ['categories' => $category]);
    }

    /**
     * @GetMapping(path="create")
     * @param RenderInterface $render
     * @return \Psr\Http\Message\ResponseInterface
     * function:
     */
    public function create(RenderInterface $render)
    {
        return $render->render('admin.category.create');
    }

    /**
     * @PostMapping(path="store")
     * function:
     */
    public function store(CategoryRequest $request): \Psr\Http\Message\ResponseInterface
    {
        $params = $request->all();

        Category::query()->create(['title' => $params['title']]);

        return $this->response->raw('success');
    }
}
