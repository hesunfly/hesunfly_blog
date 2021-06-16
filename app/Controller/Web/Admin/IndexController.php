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

use App\Exception\DbQueryException;
use App\Model\Article;
use App\Model\Image;
use App\Model\VisitRecord;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Exception\ParallelExecutionException;
use Hyperf\Utils\Parallel;
use Qbhy\HyperfAuth\AuthMiddleware;

use function Hyperf\ViewEngine\view;

/**
 * @Controller(prefix="admin")
 * @Middleware(AuthMiddleware::class)
 * Class IndexController
 */
class IndexController extends BaseController
{
    /**
     * @GetMapping(path="")
     * function:
     */
    public function index()
    {
        $parallel = new Parallel();

        $parallel->add(function () {
            $article_count = Article::query()
                ->where('status', 1)->count();

            return ['article_count' => $article_count];
        });

        $parallel->add(function () {
            $visit_count = VisitRecord::query()->count();
            return ['visit_count' => $visit_count];
        });

        $parallel->add(function () {
            $image_count = Image::query()->count();
            return ['image_count' => $image_count];
        });

        $parallel->add(function () {
            $year = date('Y');
            $sql = "select count(id) count,MONTH (publish_at) publish_at FROM article where YEAR (publish_at) = '{$year}' GROUP BY MONTH (publish_at) ORDER BY MONTH (publish_at) DESC";
            $res = Db::select($sql);
            $temp = [];
            foreach ($res as $item) {
                if (!empty($item->publish_at)) {
                    $temp[$item->publish_at] = $item->count;
                }
            }
            $data = [];
            for ($i = 1; $i <= 12; $i++) {
                $data[$i] = isset($temp[$i]) ? (int) $temp[$i] : 0;
            }

            $statistics = json_encode(array_values($data));

            return ['statistics' => $statistics];
        });

        $arr = [];
        try {
            $res = $parallel->wait();
            foreach ($res as $item) {
                $keys = array_keys($item);
                $arr[$keys[0]] = $item[$keys[0]];
            }
        } catch (ParallelExecutionException $exception) {
            throw new DbQueryException($exception->getMessage());
        }

        return view(
            'admin.index',
            [
                'article_count' => $arr['article_count'],
                'visit_count' => $arr['visit_count'],
                'image_count' => $arr['image_count'],
                'statistics' => $arr['statistics'],
            ]
        );
    }
}
