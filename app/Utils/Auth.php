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
namespace App\Utils;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;
use Psr\Http\Message\ServerRequestInterface;

trait Auth
{
    public function getAuthConfig(): ?array
    {
        return ApplicationContext::getContainer()->get(ConfigInterface::class)->get('auth');
    }

    public static function getAnnotation(string $annotation, Dispatched $dispatched = null)
    {
        if ($dispatched === null) {
            $request = Context::get(ServerRequestInterface::class);
            $dispatched = $request->getAttribute(Dispatched::class);
        }
        [$class, $method] = $dispatched->handler->callback;
        if ($class) {
            $classMethodAnnotations = AnnotationCollector::getClassMethodAnnotation($class, $method);
            return self::getValue($classMethodAnnotations, $annotation);
        }
    }

    public static function getValue($array, $key, $default = null)
    {
        if ($key instanceof \Closure) {
            return $key($array, $default);
        }

        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }

        if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
            return $array[$key];
        }

        if (($pos = strrpos($key, '.')) !== false) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (is_object($array)) {
            // this is expected to fail if the property does not exist, or __get() is not implemented
            // it is not reliably possible to check whether a property is accessible beforehand
            return $array->{$key};
        }

        if (is_array($array)) {
            return (isset($array[$key]) || array_key_exists($key, $array)) ? $array[$key] : $default;
        }

        return $default;
    }


    public function getConfig()
    {
        return self::getValue($this->getAuthConfig(), 'api', []);
    }

}
