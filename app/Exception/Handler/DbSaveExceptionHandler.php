<?php

namespace App\Exception\Handler;

use App\Exception\DbSaveException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\Utils\ApplicationContext;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class DbSaveExceptionHandler extends ExceptionHandler
{

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();
        return ApplicationContext::getContainer()->get(\Hyperf\HttpServer\Contract\ResponseInterface::class)->raw($throwable->getMessage())->withStatus(500);
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof DbSaveException;
    }
}
