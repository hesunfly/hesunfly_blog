<?php

declare(strict_types=1);

namespace App\Service;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Nsq\Nsq;

class NsqService extends Service
{

    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function push($topic, $msg)
    {
        $nsq = make(Nsq::class);

        try {
            $nsq->publish($topic, serialize($msg));
        } catch (\Throwable $exception) {
            $this->logger->warning('nsq 消息推送错误，错误信息:' . $exception->getMessage());
        }
    }

}
