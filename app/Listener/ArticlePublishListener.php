<?php

namespace App\Listener;

use App\Event\ArticlePublishEvent;
use App\Model\Subscribe;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\View\RenderInterface;

class ArticlePublishListener implements ListenerInterface
{

    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function listen(): array
    {
        return [
            ArticlePublishEvent::class,
        ];
    }

    public function process(object $event)
    {
        //发送订阅邮件
        $article = $event->article;

        $mail = getEmail();

        $mail->Subject = '新文章发布了！';

        $view = make(RenderInterface::class)->getContents('emails.article_publish', ['article' => $article]);

        $mail->MsgHTML($view);

        $sub = Subscribe::query()->where('status', 1)->pluck('email')->toArray();

        if (count($sub) > 0) {
            foreach ($sub as $item) {
                $mail->AddAddress($item); // 收件人
            }
        }

        $result = $mail->Send();

        if (!$result) {
            $error = $mail->ErrorInfo;
            $this->logger->warning('订阅邮箱发送失败，错误信息:' . $error);
        }
    }
}