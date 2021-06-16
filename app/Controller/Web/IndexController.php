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
use App\Event\UserSubscribeEvent;
use App\Exception\ValidateException;
use App\Middleware\VisitRecordMiddleware;
use App\Model\Article;
use App\Model\EmailConfirmCode;
use App\Model\Page;
use App\Model\Subscribe;
use App\Request\SubscribeRequest;
use App\Service\CacheService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
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
    public function index(RequestInterface $request, RenderInterface $render)
    {
        $keyword = $request->input('keyword');
        $articles = Article::query()
            ->with('category')
            ->where('status', 1)
            ->when(
                $keyword,
                function ($query, $keyword) {
                    $query->where('title', 'like', "%{$keyword}%");
                }
            )
            ->orderByDesc('publish_at')
            ->paginate((int)make(CacheService::class)->getConfig('page_size'));

        return $render->render(
            'index',
            [
                'articles' => $articles,
                'keyword' => $keyword,
            ]
        );
    }

    /**
     * @GetMapping(path="/article")
     * @return \Psr\Http\Message\ResponseInterface
     *                                             function:
     */
    public function show(RequestInterface $request, RenderInterface $render)
    {
        $slug = $request->input('slug');
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
     * @GetMapping(path="/page")
     * function:
     */
    public function page(RequestInterface $request)
    {
        $slug = $request->input('slug');

        if (empty($slug)) {
            return abort(404);
        }

        $page = Page::query()
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        return view('page', ['page' => $page]);
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

    /**
     * @PostMapping(path="/subscribe")
     * @param SubscribeRequest $request
     * @param ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     * function:
     */
    public function subscribe(SubscribeRequest $request, ResponseInterface $response)
    {
        $email = $request->input('email');
        Subscribe::create(
            [
                'email' => $email,
            ]
        );

        go(
            function () use ($email) {
                $this->eventDispatcher->dispatch(new UserSubscribeEvent($email));
            }
        );

        return $response->raw('success');
    }

    /**
     * @GetMapping(path="/subscribe/confirm")
     * @return \Hyperf\ViewEngine\Contract\FactoryInterface|\Hyperf\ViewEngine\Contract\ViewInterface
     * function:
     */
    public function confirm(SubscribeRequest $request)
    {
        $email = $request->input('email');
        $key = $request->input('key');
        $code = $request->input('code');
        $confirm = EmailConfirmCode::where(
            [
                'email' => $email,
                'key' => $key,
                'code' => $code,
            ]
        )->first();

        $msg = '参数错误！';

        if ($confirm) {
            if ($confirm->status == 1) {
                $msg = '您已确认订阅，请勿重复确认！';
            } elseif ($confirm->status == -1) {
                $confirm->update(['status' => 1]);
                Subscribe::where(['email' => $email])->update(['status' => 1]);
                $msg = '确认订阅成功，您可以收到我的文章更新通知了！';
            }
        }

        return view('confirm')->with(['msg' => $msg]);
    }
}
