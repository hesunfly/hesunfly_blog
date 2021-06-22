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

use App\Model\Image;
use App\Request\UploadImageRequest;
use App\Service\CacheService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\Str;
use Qbhy\HyperfAuth\AuthMiddleware;
use App\Middleware\AssignAuthInfoMiddleware;

use function Hyperf\ViewEngine\view;

/**
 * @Controller(prefix="admin/upload")
 * @Middleware(AuthMiddleware::class)
 * @Middleware(AssignAuthInfoMiddleware::class)
 * Class UploadController
 */
class UploadController extends BaseController
{
    /**
     * @PostMapping(path="image")
     * function:
     * @throws \League\Flysystem\FileExistsException
     */
    public function image(UploadImageRequest $request, ResponseInterface $response, \League\Flysystem\Filesystem $filesystem): \Psr\Http\Message\ResponseInterface
    {
        $image = $request->file('image');
        $extension = strtolower($image->getExtension());
        $filename = 'hesunfly-blog' . '-' . time() . '-' . Str::random(10) . '.' . $extension;
        $filePath = 'upload/' . 'image' . '/' . date('Y-m') . '/';
        $access_path = '/' . $filePath . $filename;

        go(function () use ($image, $filesystem, $filename, $filePath, $access_path) {
            $stream = fopen($image->getRealPath(), 'r+');
            $filesystem->writeStream($filePath . $filename, $stream);
            fclose($stream);
            Image::query()->create(
                [
                    'name' => $filename,
                    'disk' => 'local',
                    'size' => $image->getSize(),
                    'path' => $access_path,
                ]
            );
        });

        saveSysOperationLog('图片管理', '上传图片', '上传了图片，图片链接: ' . $access_path);

        return $response->json(['url' => $access_path]);
    }

    /**
     * @GetMapping("/admin/image")
     * @return \Hyperf\ViewEngine\Contract\FactoryInterface|\Hyperf\ViewEngine\Contract\ViewInterface
     * function:
     */
    public function index()
    {
        $images = Image::query()->orderByDesc('id')->paginate(make(CacheService::class)->getConfig('page_size'));

        return view('admin.image.index', ['images' => $images]);
    }
}
