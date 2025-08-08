<?php

namespace ModPath\Router;

use ReflectionClass;
use ModPath\Http\Request;
use ModPath\Http\Response;
use ModPath\Core\Container;
use ModPath\Middleware\MiddlewareManager;
use ModPath\Attribute\Route;
use ModPath\Attribute\Middleware;
use ModPath\Attribute\Guard;
use ModPath\Attribute\Dto;
use ModPath\Attribute\Controller;

class Router
{
    private array $routes = [];
    private string $requestPath;
    private string $requestMethod;
    private Container $container;

    public function __construct()
    {
        $this->requestPath = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->container = new Container();
    }

    public function registerRoutes(array $controllers): void
    {
        foreach ($controllers as $controller) {
            $this->registerRoutesFromController($controller);
        }
    }

    public function registerRoutesFromController(string $controllerClass): void
    {
        $refClass = new ReflectionClass($controllerClass);

        // Class can have Prefix
        $classPrefix = '';
        $classMiddlewares = [];

        if ($prefixAttr = $refClass->getAttributes(Controller::class)) {
            $prefixInstance = $prefixAttr[0]->newInstance();
            $classPrefix = $prefixInstance->path ?? '';
            $classMiddlewares = $prefixInstance->middleware ?? [];
        }

        foreach ($refClass->getMethods() as $method) {
            foreach ($method->getAttributes(Route::class) as $attr) {
                $route = $attr->newInstance();

                $middlewares = MiddlewareManager::getMiddlewares(
                    middlewares: $method->getAttributes(Middleware::class),
                    prefixMiddleware: $classMiddlewares
                );

                $guards = MiddlewareManager::getMiddlewares(
                    middlewares: $method->getAttributes(Guard::class),
                    prefixMiddleware: $classMiddlewares
                );

                $dtos = MiddlewareManager::getMiddlewares(
                    middlewares: $method->getAttributes(Dto::class),
                    prefixMiddleware: $classMiddlewares
                );

                $this->routes[] = [
                    'pattern' => $classPrefix . $route->path,
                    'method' => strtoupper($route->method),
                    'controller' => $controllerClass,
                    'action' => $method->getName(),
                    'middlewares' => $middlewares,
                    'guards' => $guards,
                    'dtos' => $dtos
                ];
            }
        }
    }

    public function dispatch(): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($this->requestMethod)) {
                continue;
            }

            [$regex, $paramTypes] = RouterParams::buildRegexFromRoute($route['pattern']);

            if (preg_match($regex, $this->requestPath, $matches)) {

                MiddlewareManager::verify($route['middlewares']);
                MiddlewareManager::verify($route['guards']);
                MiddlewareManager::verify($route['dtos']);

                $controller = $this->container->resolve($route['controller']);
                $params = RouterParams::extractTypedParams($matches, $paramTypes);

                $request = new Request($params);
                $response = new Response();

                call_user_func_array([$controller, $route['action']], [$request, $response]);
                return;
            }
        }

        // Fallbacks: 404 or SPA
        if (
            $_SERVER['REQUEST_URI'] !== '/404'
            && str_contains($_SERVER['REQUEST_URI'], '/api/')
        ) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        if (
            isset($_SERVER['SPA'])
            && $_SERVER['SPA']['active']
            && !str_contains($_SERVER['REQUEST_URI'], '/api/')
        ) {
            $_SERVER['SPA']['dispatch']();
        }
    }
}
