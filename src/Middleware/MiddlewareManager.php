<?php

namespace Forgeon\Middleware;

use DomainException;

class MiddlewareManager
{
    public static function verify(array $middlewares)
    {
        foreach ($middlewares as $middlewareClass) {

            $middleware = new $middlewareClass();

            if (!$middleware->handle()) {
                throw new DomainException("Middleware blocked the request.");
            }
        }
    }

    public static function getMiddlewares(array $middlewares, string | null $prefixMiddleware = null)
    {
        $middlewaresArray = [];

        if ($prefixMiddleware !== null) {
            $middlewaresArray[] = $prefixMiddleware;
        }

        foreach ($middlewares as $middlewareAttr) {
            $middlewaresArray[] = $middlewareAttr->newInstance()->className;
        }

        return $middlewaresArray;
    }
}
