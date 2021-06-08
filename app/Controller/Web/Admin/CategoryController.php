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

use App\Model\Category;
use App\Request\CategoryRequest;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\View\RenderInterface;
use Qbhy\HyperfAuth\AuthMiddleware;

use function Hyperf\ViewEngine\view;

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
    public function index()
    {
        $category = Category::query()->select('id', 'title', 'count', 'created_at')->get();
        return view('admin.category.index', ['categories' => $category]);
    }

    /**
     * @GetMapping(path="create")
     * @param RenderInterface $render
     * function:
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * @PostMapping(path="store")
     * function:
     */
    public function store(CategoryRequest $request, ResponseInterface $response)
    {
        $params = $request->all();

        Category::query()->create(['title' => $params['title']]);

        return $response->raw('success');
    }
}
