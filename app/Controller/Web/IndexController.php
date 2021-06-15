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

use App\Controller\AbstractController;
use App\Event\ArticleShowEvent;
use App\Exception\ValidateException;
use App\Middleware\VisitRecordMiddleware;
use App\Model\Article;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\View\RenderInterface;
use Hyperf\Di\Annotation\Inject;
use Psr\EventDispatcher\EventDispatcherInterface;
use Qbhy\HyperfAuth\AuthManager;

use function Hyperf\ViewEngine\view;

/**
 * @Controller(prefix="/")
 * @Middleware(VisitRecordMiddleware::class)
 * Class IndexController
 */
class IndexController extends AbstractController
{

    /**
     * @Inject
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @Inject
     * @var AuthManager
     */
    protected $auth;

    /**
     * @GetMapping(path="/")
     * @return \Psr\Http\Message\ResponseInterface
     *                                             function:
     */
    public function index(RenderInterface $render)
    {
        $articles = Article::query()
            ->with('category')
            ->where('status', 1)
            ->orderByDesc('publish_at')
            ->paginate(config('app.page_size'));

        return $render->render('index', ['articles' => $articles]);
    }

    /**
     * @GetMapping(path="/article")
     * @return \Psr\Http\Message\ResponseInterface
     *                                             function:
     */
    public function show(RenderInterface $render)
    {
        $slug = $this->request->input('slug');
        if (empty($slug)) {
            throw new ValidateException('slug 参数为空');
        }
        $query = Article::query()
            ->where('slug', $slug);

        if ($this->auth->check()) {
            $article = $query->firstOrFail();
        } else {
            $article = $query->where('status', 1)
                ->firstOrFail();
        }

        if ($article->status == 1) {
            $article->visit_ip = get_client_ip();
            go(
                function () use ($article) {
                    $this->eventDispatcher->dispatch(new ArticleShowEvent($article));
                }
            );
        }

        return $render->render('article', ['article' => $article, 'auth' => $this->auth->check()]);
    }

    /**
     * @GetMapping(path="/404")
     * @return \Hyperf\ViewEngine\Contract\FactoryInterface|\Hyperf\ViewEngine\Contract\ViewInterface
     * function:
     */
    public function errorNotFound()
    {
        return view('404');
    }
}
