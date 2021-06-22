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
use App\Model\Page;
use App\Request\PageRequest;
use App\Service\CacheService;
use Carbon\Carbon;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\Str;
use Qbhy\HyperfAuth\AuthMiddleware;

use function Hyperf\ViewEngine\view;

/**
 * @Controller(prefix="admin/page")
 * @Middleware(AuthMiddleware::class)
 * Class IndexController
 */
class PageController extends BaseController
{

    /**
     * @GetMapping(path="")
     * function:
     */
    public function index()
    {
        $pages = Page::query()->paginate(make(CacheService::class)->getConfig('page_size'));

        return view(
            'admin.page.index',
            ['pages' => $pages]
        );
    }

    /**
     * @GetMapping(path="create")
     * function:
     */
    public function create()
    {
        return view('admin.page.create');
    }

    /**
     * @PostMapping(path="store")
     * function:
     */
    public function store(PageRequest $request, ResponseInterface $response)
    {
        $params = $request->all();
        Page::query()->create($params);

        saveSysOperationLog('页面模块', '创建页面', '创建了页面，页面标题: ' . $params['title'], $request);

        return $response->raw('success')->withStatus(201);
    }

    /**
     * @GetMapping(path="edit")
     * @param RequestInterface $request
     * @return \Hyperf\ViewEngine\Contract\FactoryInterface|\Hyperf\ViewEngine\Contract\ViewInterface
     * function:
     */
    public function edit(RequestInterface $request)
    {
        $id = $request->input('id');
        $page = Page::query()
            ->where('id', $id)
            ->firstOrFail();

        return view('admin.page.edit', ['page' => $page]);
    }

    /**
     * @PutMapping(path="save")
     * function:
     */
    public function save(PageRequest $request, ResponseInterface $response)
    {
        $params = $request->all();
        $page = Page::query()->where('id', $params['id'])->firstOrFail();
        $page->update($params);

        saveSysOperationLog('页面模块', '编辑页面', '编辑了页面，页面标题: ' . $params['title'], $request);

        return $response->raw('success')->withStatus(200);
    }

    /**
     * @DeleteMapping(path="delete")
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     * function:
     */
    public function delete(RequestInterface $request, ResponseInterface $response): \Psr\Http\Message\ResponseInterface
    {
        $id = $request->input('id');

        if (empty($id)) {
            throw new ValidateException('id 参数为空');
        }

        $page = Page::query()->where('id', $id)->firstOrFail();
        $page->delete();

        saveSysOperationLog('页面模块', '删除页面', '删除了页面，页面标题: ' . $page->title, $request);

        return $response->raw('success')->withStatus(204);
    }
}
