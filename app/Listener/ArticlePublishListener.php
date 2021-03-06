<?php

namespace App\Listener;

use App\Event\ArticlePublishEvent;
use App\Model\Subscribe;
use App\Service\EmailService;
use App\Service\NsqService;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Nsq\Result;
use Hyperf\View\RenderInterface;

class ArticlePublishListener implements ListenerInterface
{
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

        if (env("NSQ_ENABLE")) {
            make(NsqService::class)->push('hesunfly_blog_sendSubscribe', $article);
            return true;
        }

        $sub = Subscribe::query()->where('status', 1)->pluck('email')->toArray();

        if (count($sub) == 0) {
            return false;
        }

        $mail = make(EmailService::class)->getEmail();

        $view = make(RenderInterface::class)
            ->getContents('emails.article_publish', ['article' => $article]);

        $mail->Subject = '新文章发布了！';
        $mail->MsgHTML($view);

        foreach ($sub as $item) {
            $mail->AddAddress($item); // 收件人
        }

        $result = $mail->Send();

        if (!$result) {
            $error = $mail->ErrorInfo;
            $this->logger->warning('订阅邮箱发送失败，错误信息:' . $error);
        }
    }
}