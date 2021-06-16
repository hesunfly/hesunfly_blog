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
use App\Model\Ad;
use App\Model\Config;
use App\Model\Subscribe;
use App\Model\VisitRecord;
use App\Request\AdRequest;
use App\Request\SubscribeRequest;
use App\Service\CacheService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
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
        $record = VisitRecord::query()->orderByDesc('id')->paginate(make(CacheService::class)->getConfig('page_size'));

        return view('admin.visit_record', ['record' => $record]);
    }

    /**
     * @GetMapping(path="adIndex")
     * function:
     */
    public function adIndex()
    {
        $ads = Ad::query()->get();
        return view('admin.ad.index', ['ads' => $ads]);
    }

    /**
     * @GetMapping(path="adCreate")
     * function:
     */
    public function adCreate()
    {
        return view('admin.ad.create');
    }
    
    /**
     * @PostMapping(path="adStore")
     * function:
     */
    public function adStore(AdRequest $request, ResponseInterface $response)
    {
        $params = $request->all();

        go(function () use ($params) {
            Ad::query()->create($params);
            make(CacheService::class)->deleteAds();
        });

        return $response->raw('success')->withStatus(201);
    }

    /**
     * @GetMapping(path="adEdit")
     * function:
     */
    public function adEdit(RequestInterface $request)
    {
        $id = $request->input('id');
        if (! $id) {
            throw new ValidateException('id 参数为空');
        }

        $ad = Ad::query()->where('id', $id)->firstOrFail();

        return view('admin.ad.edit', ['ad' => $ad]);
    }

    /**
     * @PutMapping(path="adSave")
     * function:
     */
    public function adSave(AdRequest $request, ResponseInterface $response)
    {
        $params = $request->all();

        $ad = Ad::query()->where('id', $params['id'])->firstOrFail();

        $ad->update($params);

        make(CacheService::class)->deleteAds();

        return $response->raw('success')->withStatus(200);
    }

    /**
     * @DeleteMapping(path="adDelete")
     * function:
     */
    public function adDelete(RequestInterface $request, ResponseInterface $response)
    {
        $id =$request->input('id');
        if (! $id) {
            throw new ValidateException('id 参数为空');
        }

        $ad = Ad::query()->where('id', $id)->firstOrFail();

        $ad->delete();

        make(CacheService::class)->deleteAds();

        return $response->raw('success')->withStatus(204);
    }

    /**
     * @GetMapping(path="configIndex")
     * @return \Hyperf\ViewEngine\Contract\FactoryInterface|\Hyperf\ViewEngine\Contract\ViewInterface
     * function:
     */
    public function configIndex()
    {
        $config = Config::query()->first()->toArray();

        $config_fmt = [];
        foreach ($config as $k => $item) {
            $config_fmt[] = [
                'name' => $k,
                'value' => $item,
                'title' => Config::$setting_title[$k]['title'],
                'en_title' => Config::$setting_title[$k]['en_title']
            ];
        }

        return view('admin.config')->with(['config' => $config_fmt]);
    }

    /**
     * @PutMapping(path="configSave")
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     * function:
     */
    public function configSave(RequestInterface $request, ResponseInterface $response)
    {
        $request_data = $request->all();

        foreach ($request_data as $k => $item) {
            $request_data[$k] = empty($item) ? '' : $item;
        }

        Config::first()->update($request_data);

        make(CacheService::class)->deleteConfig();

        return $response->raw('success');
    }

    /**
     * @GetMapping(path="subscribeIndex")
     * function:
     */
    public function subscribeIndex()
    {
        $subscribes = Subscribe::query()->paginate(make(CacheService::class)->getConfig('page_size'));
        return view('admin.subscribes', ['subscribes' => $subscribes]);
    }

    /**
     * @PutMapping(path="subscribeStatus")
     * @param SubscribeRequest $request
     * @param ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     * function:
     */
    public function subscribeStatus(SubscribeRequest $request, ResponseInterface $response)
    {
        $status = $request->input('status');
        $id = $request->input('id');

        $sub = Subscribe::query()->where('id', $id)->firstOrFail();
        $sub->status = $status;
        $sub->save();

        return $response->raw('success');
    }
}
