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

use App\Request\UploadImageRequest;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\Utils\Str;
use Qbhy\HyperfAuth\AuthMiddleware;

/**
 * @Controller(prefix="admin/upload")
 * @Middleware(AuthMiddleware::class)
 * Class UploadController
 */
class UploadController extends BaseController
{
    /**
     * @PostMapping(path="image")
     * function:
     * @throws \League\Flysystem\FileExistsException
     */
    public function image(UploadImageRequest $request, \League\Flysystem\Filesystem $filesystem): \Psr\Http\Message\ResponseInterface
    {
        $image = $request->file('image');

        $stream = fopen($image->getRealPath(), 'r+');

        $extension = strtolower($image->getExtension());
        $filename = 'hesunfly-blog' . '-' . time() . '-' . Str::random(10) . '.' . $extension;
        $filePath = 'upload/' . 'image' . '/' . date('Y-m');
        $filesystem->writeStream($filePath . $filename, $stream);
        fclose($stream);

        $access_path = '/' . $filePath . $filename;

        return $this->response->json(['url' => $access_path]);
    }
}
