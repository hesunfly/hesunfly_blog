<?php

namespace App\Listener;

use App\Event\ArticleCreateEvent;
use App\Model\Article;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Hyperf\Event\Contract\ListenerInterface;

class ArticleCreateListener implements ListenerInterface
{

    public function listen(): array
    {
        return [
            ArticleCreateEvent::class,
        ];
    }

    public function process(object $event)
    {
        //生成二维码
        $article = $event->article;

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
}