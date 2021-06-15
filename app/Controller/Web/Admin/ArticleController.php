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
use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\Exception\ParallelExecutionException;
use Hyperf\Utils\Parallel;
use Hyperf\Utils\Str;
use Psr\EventDispatcher\EventDispatcherInterface;
use Qbhy\HyperfAuth\AuthMiddleware;

use function Hyperf\ViewEngine\view;

/**
 * @Controller(prefix="admin/article")
 * @Middleware(AuthMiddleware::class)
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
        $parallel->add(
            function () use ($category) {
                $articles = Article::query()
                    ->select('id', 'category_id', 'title', 'slug', 'status', 'view_count', 'publish_at', 'created_at')
                    ->with('category')
                    ->when(
                        $category,
                        function ($query, $category) {
                            $query->where('category_id', $category);
                        }
                    )
                    ->orderByDesc('id')
                    ->paginate(10);

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
            ['articles' => $arr['articles'], 'categories' => $arr['category'], 'category_id' => $category]
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

        if ($article->publish_status == -1 && $publish_status == 1) {
            $params['publish_at'] = Carbon::now();
            $this->eventDispatcher->dispatch(new ArticlePublishEvent($article));
        }

        if ($article->category_id != $params['category_id']) {
            Category::query()->where('id', $article->category_id)->decrement('count');
            Category::query()->where('id', $params['category_id'])->increment('count');
        }

        $article = $article->update($params);
        //文章编辑事件

        return $response->raw('success')->withStatus(200);
    }

    public function delete(RequestInterface $request, ResponseInterface $response)
    {
        $id = $request->input('id');

        if (empty($id)) {
            throw new ValidateException('id 参数为空');
        }

        $article = Article::query()->where('id', $id)->firstOrFail();
        $category_id = $article->category_id;
        $article->delete();

        //相关分类文章数减一
        $this->eventDispatcher->dispatch(new ArticleDeleteEvent($category_id));

        return $response->raw('success');
    }

    private function getCategory()
    {
        return Category::query()->select('id', 'title')->get();
    }
}
