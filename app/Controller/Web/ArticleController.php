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

use App\Exception\DbQueryException;
use App\Exception\DbSaveException;
use App\Model\Article;
use App\Model\Category;
use App\Request\ArticleRequest;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\Utils\Exception\ParallelExecutionException;
use Hyperf\Utils\Parallel;
use Hyperf\Utils\Str;
use Hyperf\View\RenderInterface;
use Qbhy\HyperfAuth\AuthMiddleware;

/**
 * @Controller(prefix="admin/article")
 * @Middleware(AuthMiddleware::class)
 * Class IndexController
 */
class ArticleController extends BaseController
{
    /**
     * @GetMapping(path="")
     * function:
     */
    public function index(RenderInterface $render)
    {
        $parallel = new Parallel();
        $parallel->add(
            function () {
                $articles = Article::query()
                    ->select('id', 'category_id', 'title', 'slug', 'status', 'view_count', 'publish_at', 'created_at')
                    ->with('category')
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

        return $render->render(
            'admin.article.index',
            ['articles' => $arr['articles'], 'categories' => $arr['category']]
        );
    }

    /**
     * @GetMapping(path="create")
     * @return \Psr\Http\Message\ResponseInterface
     * function:
     */
    public function create(RenderInterface $render)
    {
        $category = $this->getCategory();
        return $render->render('admin.article.create', ['category' => $category]);
    }

    /**
     * @PostMapping(path="store")
     * function:
     */
    public function store(ArticleRequest $request)
    {
        $params = $request->all();

        $params['slug'] = $params['slug'] ?: 'article_' . Str::random(10);

        Db::beginTransaction();
        try {
            Article::query()->create($params);
            //如果文章发布了生成二维码
            if ($params['status'] == 1) {
                Category::query()->where('id', $params['category_id'])->increment('count');
            }
            Db::commit();
        } catch (\Throwable $exception) {
            Db::rollBack();
            throw new DbSaveException('文章保存失败！');
        }

        return $this->response->raw('success')->withStatus(201);
    }

    private function getCategory()
    {
        return Category::query()->select('id', 'title')->get();
    }
}
