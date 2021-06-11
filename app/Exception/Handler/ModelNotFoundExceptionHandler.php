<?php

namespace App\Exception\Handler;

use App\Exception\DbQueryException;
use Hyperf\Database\Model\ModelNotFoundException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\Utils\ApplicationContext;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ModelNotFoundExceptionHandler extends ExceptionHandler
{

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();
        return ApplicationContext::getContainer()->get(\Hyperf\HttpServer\Contract\ResponseInterface::class)->raw('数据不存在！')->withStatus(404);
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ModelNotFoundException;
    }
}
