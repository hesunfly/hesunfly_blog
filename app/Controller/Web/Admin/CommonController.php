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

use App\Model\Article;
use App\Model\VisitRecord;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Qbhy\HyperfAuth\AuthMiddleware;

use function Hyperf\ViewEngine\view;

/**
 * @Controller(prefix="admin/common")
 * @Middleware(AuthMiddleware::class)
 * Class IndexController
 */
class CommonController extends BaseController
{
    /**
     * @GetMapping(path="visitRecord")
     * function:
     */
    public function visitRecord()
    {
        $record = VisitRecord::query()->orderByDesc('id')->paginate(config('app.page_size'));

        return view('admin.ip', ['record' => $record]);
    }
}
