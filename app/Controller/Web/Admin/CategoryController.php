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

use App\Exception\ValidateException;
use App\Model\Article;
use App\Model\Category;
use App\Request\CategoryRequest;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\Context;
use Qbhy\HyperfAuth\AuthMiddleware;
use App\Middleware\AssignAuthInfoMiddleware;

use function Hyperf\ViewEngine\view;

/**
 * @Controller(prefix="admin/category")
 * @Middleware(AuthMiddleware::class)
 * @Middleware(AssignAuthInfoMiddleware::class)
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
     *                                function:
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
        $category = Category::query()->create(['title' => $params['title']]);

        Context::set('source_id', $category->id);
        saveSysOperationLog('分类模块', '创建分类', '创建了分类，分类标题: ' . $params['title']);

        return $response->raw('success');
    }

    /**
     * @GetMapping(path="edit")
     *                                function:
     */
    public function edit(RequestInterface $request)
    {
        $id = $request->input('id');

        if (!$id) {
            throw new ValidateException('id 参数不存在！');
        }

        $category = Category::query()->where('id', $id)->firstOrFail();
        return view('admin.category.edit', ['category' => $category]);
    }

    /**
     * @PutMapping(path="save")
     * function:
     */
    public function save(CategoryRequest $request, ResponseInterface $response)
    {
        $params = $request->all();

        $category = Category::query()->where('id', $params['id'])->firstOrFail();
        $old_title = $category->title;

        go(
            function () use ($category, $params) {
                $category->update(['title' => $params['title']]);
            }
        );

        Context::set('source_id', $params['id']);
        saveSysOperationLog('分类模块', '编辑分类', '编辑了分类，原分类标题: ' . $old_title . ', 修改后标题：' . $params['title']);

        return $response->raw('success');
    }

    /**
     * @DeleteMapping(path="delete")
     * function:
     */
    public function delete(RequestInterface $request, ResponseInterface $response)
    {
        $id = $request->input('id');

        if (!$id) {
            throw new ValidateException('id 参数为空');
        }

        $category = Category::query()->where('id', $id)->firstOrFail();

        $has = Article::query()->where('category_id', $id)->count();
        if ($has > 0) {
            return $response->raw('当前分类存在文章，不可删除')->withStatus(403);
        }
        $title = $category->title;

        $category->delete();

        Context::set('source_id', $id);
        saveSysOperationLog('分类模块', '删除分类', '删除了分类，分类标题: ' . $title);

        return $response->raw('success')->withStatus(204);
    }
}
