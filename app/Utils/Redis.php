<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Utils;

use Hyperf\Redis\RedisFactory;
use Hyperf\Utils\ApplicationContext;

class Redis
{
    protected $poolName = 'default';

    public static function getPoolName(): string
    {
        return (new static())->poolName;
    }

    public static function getContainer()
    {
        return ApplicationContext::getContainer()->get(RedisFactory::class)->get('default');
    }
}
