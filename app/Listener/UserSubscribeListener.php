<?php

namespace App\Listener;

use App\Event\UserSubscribeEvent;
use App\Model\EmailConfirmCode;
use App\Model\Subscribe;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\View\RenderInterface;

class UserSubscribeListener implements ListenerInterface
{

    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function listen(): array
    {
        return [
            UserSubscribeEvent::class,
        ];
    }

    public function process(object $event)
    {
        //发送订阅邮件
        $email = $event->email;

        $mail = getEmail();

        $mail->Subject = '博客订阅确认验证码！';

        $key = 'email_subscribe_confirm_' . $email;
        $code = mt_rand(111111, 999999);
        $data = [
            'email' => $email,
            'code' => $code,
            'key' => $key,
        ];

        EmailConfirmCode::create($data);

        $view = make(RenderInterface::class)->getContents('emails.subscribe_confirm', ['data' => $data]);

        $mail->MsgHTML($view);
        $mail->AddAddress($email); // 收件人
        $result = $mail->Send();

        if (!$result) {
            $error = $mail->ErrorInfo;
            $this->logger->warning('订阅验证邮箱发送失败，错误信息:' . $error);
        }
    }
}