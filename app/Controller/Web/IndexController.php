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
use App\Exception\ValidateException;
use App\Model\Article;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\View\RenderInterface;

/**
 * @Controller(prefix="/")
 * Class IndexController
 */
class IndexController extends AbstractController
{
    /**
     * @GetMapping(path="/")
     * @return \Psr\Http\Message\ResponseInterface
     * function:
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
     * @param RenderInterface $render
     * @return \Psr\Http\Message\ResponseInterface
     * function:
     */
    public function show(RenderInterface $render)
    {
        $slug = $this->request->input('slug');
        if (empty($slug)) {
            throw new ValidateException('slug 参数为空');
        }
        $article = Article::query()
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        return $render->render('article', ['article' => $article]);
    }
}
