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
use Hyperf\Redis\Redis;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;
use PHPMailer\PHPMailer\PHPMailer;
use Torann\GeoIP\Support\HttpClient;

function guid()
{
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    }
    $charid = md5(uniqid((string) rand(), true));
    $hyphen = ''; // "-"
    $uuid = '';
    $uuid .= substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr(
        $charid,
        12,
        4
    ) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
    return $uuid;
}

function get_client_ip()
{
    $request = Context::get(\Psr\Http\Message\ServerRequestInterface::class);
    $x_real_ip = $request->getHeaderLine('x-real-ip');
    if ($x_real_ip) {
        $ip = $x_real_ip;
        return filter_var($ip, FILTER_VALIDATE_IP) ?: '127.0.0.1';
    }
    if (! empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else {
        // for php-cli(phpunit etc.)
        $ip = defined('PHPUNIT_RUNNING') ? '127.0.0.1' : gethostbyname(gethostname());
    }
    return filter_var($ip, FILTER_VALIDATE_IP) ?: '127.0.0.1';
}

function is_mobile($string = '')
{
    $pattern = '/^[1]([3456879]{1})([0-9]{9})$/';
    return preg_match($pattern, $string) == 1;
}

/**
 * @param string $mobile
 * @return string
 *                隐藏手机号
 */
function hidden_mobile($mobile = ''): string
{
    if (is_mobile($mobile)) {
        return substr($mobile, 0, 3) . '****' . substr($mobile, 7);
    }
    return $mobile;
}

function array_by_group($array, $key): array
{
    $group = [];
    if ($array) {
        foreach ($array as $item) {
            $group[$item[$key]][] = $item;
        }
    }
    return $group;
}

function work_time($work_time)
{
    if (empty($work_time)) {
        return 1;
    }
    $work_year = substr($work_time, 0, 4);
    $new_year = date('Y');
    return $new_year - $work_year > 0 ? $new_year - $work_year : 1;
}

function rand_code($length = 32, $type = 'null')
{
    $arr = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    if ($type == 'num') {
        // $arr = "0123456789";
        $code = mt_rand(100000, 999999);
    //$code = mt_rand(1000,9999);
    } else {
        $count = strlen($arr) - 1;
        $code = '';
        for ($i = 0; $i < $length; ++$i) {
            $code .= $arr[rand(0, $count)];
        }
    }
    return $code;
}

function search_array_value($arr, $key, $field, $search_value)
{
    $temp_arr = [];
    foreach ($arr as $value) {
        if ($value[$key] === $search_value) {
            $temp_arr[] = $value[$field];
        }
    }
    return $temp_arr;
}

/*生成订单号
 * @param $type
 * @return string
 */
function create_order_no($type)
{
    return $type . date('YmdHis') . time() . substr(microtime(), 2, 5);
}

/**
 * 截取订单号.
 * @param mixed $oid
 */
function substr_order_no($oid)
{
    return substr($oid, 0, 2);
}

function get_content_img($content)
{
    $pattern = "/<[img|IMG].*?src=[\\'|\"](.*?(?:[\\.gif|\\.jpg]))[\\'|\"].*?[\\/]?>/";
    preg_match_all($pattern, $content, $match);
    if (! empty($match[1])) {
        return $match[1][0];
    }
    return false;
}

/**
 * 处理json key 缺少引号.
 * @param $str
 * @param bool $mode
 * @return mixed
 */
function ext_json_decode($str, $mode = false)
{
    if (preg_match('/\w:/', $str)) {
        $str = preg_replace('/(\w+):/is', '"$1":', $str);
    }
    return json_decode($str, $mode);
}

function getRedis()
{
    return ApplicationContext::getContainer()->get(Redis::class);
}

function isDesktop(): bool
{
    $detect = new Mobile_Detect();

    $request = ApplicationContext::getContainer()->get(\Hyperf\HttpServer\Contract\RequestInterface::class);
    $ua = $request->getHeaderLine('User-Agent');
    $headers =  $request->getHeaders();
    return ! $detect->isMobile($ua,$headers) && ! $detect->isTablet($ua,$headers);
}


function abort($code = 404)
{
    switch ($code) {
        case 404:
            return ApplicationContext::getContainer()->get(\Hyperf\HttpServer\Contract\ResponseInterface::class)->redirect('/404');
    }
}

/**
 * @return PHPMailer
 * function:获取email发送对象
 */
function getEmail()
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