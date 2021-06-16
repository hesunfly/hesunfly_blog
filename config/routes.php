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
            ->get(\Hyperf\HttpServer\Contract\ResponseInterface::class)->raw('初始化完成! email：admin@163.com  password: 123456');
    }
);