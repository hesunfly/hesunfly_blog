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

use App\Model\Article;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Hyperf\HttpServer\Router\Router;


Router::get(
    '/init',
    function () {
        \App\Model\User::create(
            [
                'user_name' => 'admin',
                'email' => 'admin@163.com',
                'password' => \HyperfExt\Hashing\Hash::make('MTIzNDU2'),
            ]
        );

        \App\Model\Config::query()->create(['id' => 1]);

        return \Hyperf\Utils\ApplicationContext::getContainer()
            ->get(\Hyperf\HttpServer\Contract\ResponseInterface::class)->raw('初始化完成! 后台账号密码为：email：admin@163.com  password: 123456');
    }
);

Router::get('/sync', function () {
    $articles = \App\Model\Article::query()->get();

    foreach ($articles as $article) {


    $url = env('APP_URL') . 'article?slug=' . $article->slug;
    $file_path = BASE_PATH . '/public/upload/' . 'qr_image' . '/' . date('Y') . '/';
    $file_name = 'article_qrcode_' . $article->id . '.png';

    $res = Builder::create()
        ->writer(new PngWriter())
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
        ->size(200)
        ->margin(10)
        ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
        ->logoPath(BASE_PATH . '/public/assets/images/hesunfly-qr.png')
        ->logoResizeToWidth(40)
        ->logoResizeToHeight(40)
        ->data($url)
        ->build();

    $path = $file_path . $file_name;
    if (! is_dir(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }

    $res->saveToFile($path);
    $access_path = '/upload/' . 'qr_image' . '/' . date('Y') . '/' . $file_name;

    Article::query()->where('id', $article->id)->update(['qr_path' => $access_path]);
    }

});