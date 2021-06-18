<?php

declare(strict_types=1);

namespace App\Nsq\Consumer;

use App\Model\Subscribe;
use App\Service\EmailService;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Nsq\AbstractConsumer;
use Hyperf\Nsq\Annotation\Consumer;
use Hyperf\Nsq\Message;
use Hyperf\Nsq\Result;
use Hyperf\View\RenderInterface;

/**
 * 接收需要发送订阅邮件验证
 * @Consumer(topic="hesunfly_blog_sendSubscribe", channel="sendSubscribe", name ="SendSubscribeConsumer", nums=1)
 */
class SendSubscribeConsumer extends AbstractConsumer
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function isEnable(): bool
    {
        return true;
    }

    public function consume(Message $payload): ?string
    {
        $article = unserialize($payload->getBody());

        if (! $article) {
            return Result::ACK;
        }

        $sub = Subscribe::query()->where('status', 1)->pluck('email')->toArray();

        if (count($sub) == 0) {
            return Result::ACK;
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

        return Result::ACK;
    }
}
