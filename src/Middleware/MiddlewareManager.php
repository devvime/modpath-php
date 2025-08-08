<?php

namespace ModPath\Middleware;

use DomainException;

class MiddlewareManager
{
    public static function verify(array $middlewares): void
    {
        foreach ($middlewares as $middlewareClass) {
            $middleware = new $middlewareClass();

            if (!method_exists($middleware, 'handle')) {
                throw new DomainException("Middleware '$middlewareClass' must have a handle() method.");
            }

            if (!$middleware->handle()) {
                throw new DomainException("Middleware '$middlewareClass' blocked the request.");
            }
        }
    }

    public static function getMiddlewares(array $middlewares, string|array|null $prefixMiddleware = null): array
    {
        $middlewareList = [];

        // Support for single or array middleware in prefix
        if ($prefixMiddleware) {
            $middlewareList = array_merge(
                $middlewareList,
                is_array($prefixMiddleware) ? $prefixMiddleware : [$prefixMiddleware]
            );
        }

        // Middleware defined via #[Middleware] attribute
        foreach ($middlewares as $attr) {
            $instance = $attr->newInstance();
            $middlewareList[] = $instance->className;
        }

        return $middlewareList;
    }
}
