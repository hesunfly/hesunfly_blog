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

use App\Event\ArticleCreateEvent;
use App\Event\ArticlePublishEvent;
use App\Event\ArticleDeleteEvent;
use App\Exception\DbQueryException;
use App\Exception\DbSaveException;
use App\Exception\ValidateException;
use App\Model\Article;
use App\Model\Category;
use App\Request\ArticleRequest;
use App\Service\CacheService;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\Context;
use Hyperf\Utils\Exception\ParallelExecutionException;
use Hyperf\Utils\Parallel;
use Hyperf\Utils\Str;
use Psr\EventDispatcher\EventDispatcherInterface;
use Qbhy\HyperfAuth\AuthMiddleware;
use App\Middleware\AssignAuthInfoMiddleware;

use function Hyperf\ViewEngine\view;

/**
 * @Controller(prefix="admin/article")
 * @Middleware(AuthMiddleware::class)
 * @Middleware(AssignAuthInfoMiddleware::class)
 * Class IndexController
 */
class ArticleController extends BaseController
{
    /**
     * @Inject
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @GetMapping(path="")
     * function:
     */
    public function index(RequestInterface $request)
    {
        $parallel = new Parallel();
        $category = $request->input('category');
        $keyword = $request->input('keyword');
        $page = (int)$request->input('page', 1);
        $parallel->add(
            function () use ($page, $category, $keyword) {
                $articles = Article::query()
                    ->select('id', 'category_id', 'title', 'slug', 'status', 'view_count', 'publish_at', 'created_at')
                    ->with('category')
                    ->when(
                        $category,
                        function ($query, $category) {
                            $query->where('category_id', $category);
                        }
                    )
                    ->when(
                        $keyword,
                        function ($query, $keyword) {
                            $query->where('title', 'like', "%{$keyword}%");
                        }
                    )
                    ->orderByDesc('id')
                    ->paginate(make(CacheService::class)->getConfig('page_size'), ['*'], 'page', $page);

                return ['articles' => $articles];
            }
        );
        $parallel->add(
            function () {
                return ['category' => $this->getCategory()];
            }
        );

        $arr = [];
        try {
            $res = $parallel->wait();
            foreach ($res as $item) {
                $keys = array_keys($item);
                $arr[$keys[0]] = $item[$keys[0]];
            }
        } catch (ParallelExecutionException $exception) {
            throw new DbQueryException('首页文章列表查询失败');
        }

        return view(
            'admin.article.index',
            [
                'articles' => $arr['articles'],
                'categories' => $arr['category'],
                'category_id' => $category,
                'keyword' => $keyword,
            ]
        );
    }

    /**
     * @GetMapping(path="create")
     *                                            function:
     */
    public function create()
    {
        $category = $this->getCategory();
        return view('admin.article.create', ['category' => $category]);
    }

    /**
     * @PostMapping(path="store")
     * function:
     */
    public function store(ArticleRequest $request, ResponseInterface $response)
    {
        $params = $request->all();

        $params['slug'] = $params['slug'] ?: 'article_' . Str::random(10);

        $publish_status = $params['status'];
        Db::beginTransaction();
        try {
            if ($publish_status == 1) {
                $params['publish_at'] = Carbon::now();
            }
            $article = Article::query()->create($params);
            Category::query()->where('id', $params['category_id'])->increment('count');
            Db::commit();
        } catch (\Throwable $exception) {
            Db::rollBack();
            throw new DbSaveException('文章保存失败！');
        }

        go(
            function () use ($article) {
                $this->eventDispatcher->dispatch(new ArticleCreateEvent($article));
            }
        );

        if ($publish_status == 1) {
            go(
                function () use ($article) {
                    $this->eventDispatcher->dispatch(new ArticlePublishEvent($article));
                }
            );
        }

        Context::set('source_id', $article->id);
        saveSysOperationLog('文章模块', '创建文章', '创建了新文章，文章标题: ' . $params['title']);

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
        $article = Article::query()
            ->where('id', $id)
            ->with('category')
            ->firstOrFail();

        $category = $this->getCategory();

        return view('admin.article.edit', ['article' => $article, 'category' => $category]);
    }

    /**
     * @PutMapping(path="save")
     * function:
     */
    public function save(ArticleRequest $request, ResponseInterface $response)
    {
        $params = $request->all();
        $article = Article::query()->where('id', $params['id'])->firstOrFail();
        $publish_status = $params['status'];

        if ($article->status == -1 && $publish_status == 1) {
            $params['publish_at'] = Carbon::now();
            go(
                function () use ($params) {
                    $this->eventDispatcher->dispatch(new ArticlePublishEvent($params));
                }
            );
        }

        if ($article->category_id != $params['category_id']) {
            Category::query()->where('id', $article->category_id)->decrement('count');
            Category::query()->where('id', $params['category_id'])->increment('count');
        }

        $article->update($params);

        Context::set('source_id', $params['id']);
        saveSysOperationLog('文章模块', '编辑文章', '编辑了文章，文章标题: ' . $params['title']);

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
    public function delete(RequestInterface $request, ResponseInterface $response)
    {
        $id = $request->input('id');

        if (empty($id)) {
            throw new ValidateException('id 参数为空');
        }

        $article = Article::query()->where('id', $id)->firstOrFail();
        $title = $article->title;
        $category_id = $article->category_id;
        $article->delete();

        //相关分类文章数减一
        $this->eventDispatcher->dispatch(new ArticleDeleteEvent($category_id));

        Context::set('source_id', $id);
        saveSysOperationLog('文章模块', '删除文章', '删除了文章，文章标题: ' . $title);

        return $response->raw('success');
    }

    private function getCategory()
    {
        return Category::query()->select('id', 'title')->get();
    }
}
