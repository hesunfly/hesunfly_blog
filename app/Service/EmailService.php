<?php

declare(strict_types=1);

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;

class EmailService extends Service
{
    public function getEmail(): PHPMailer
    {
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->IsSMTP();
        $mail->SMTPDebug = env('MAIL_DEBUG', 0); // 关闭SMTP调试功能
        $mail->SMTPAuth = true; // 启用 SMTP 验证功能
        $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'ssl'); // 使用安全协议
        $mail->Host = env('MAIL_HOST'); // SMTP 服务器
        $mail->Port = env('MAIL_PORT'); // SMTP服务器的端口号
        $mail->Username = env('MAIL_USERNAME'); // SMTP服务器用户名
        $mail->Password = env('MAIL_PASSWORD'); // SMTP服务器密码
        $mail->SetFrom(env('MAIL_FROM_ADDRESS'), env("MAIL_FROM_NAME")); // 邮箱，昵称

        return $mail;
    }
}
