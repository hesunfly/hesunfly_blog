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
                'user_name' => 'Hesunfly',
                'email' => 'hesunfly@163.com',
                'password' => \HyperfExt\Hashing\Hash::make('MTIzNDU2'),
            ]
        );
    }
);