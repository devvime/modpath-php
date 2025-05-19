<?php

namespace Mini;

use ReflectionClass;
use Mini\Container;
use Mini\RouterParams;
use Mini\MiddlewareManager;
use Mini\Attribute\Route;
use Mini\Attribute\Middleware;

class Router
{
    private array $routes = [];
    private string $requestPath;
    private string $requestMethod;
    private Container $container;

    public function __construct()
    {
        $this->requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->container = new Container();
    }

    public function registerRoutesFromController(string $controllerClass): void
    {
        $refClass = new ReflectionClass($controllerClass);

        foreach ($refClass->getMethods() as $method) {

            foreach ($method->getAttributes(Route::class) as $attr) {

                $route = $attr->newInstance();
                $middlewares = MiddlewareManager::getMiddlewares($method->getAttributes(Middleware::class));

                $this->routes[] = [
                    'pattern' => $route->path,
                    'method' => strtoupper($route->method),
                    'controller' => $controllerClass,
                    'action' => $method->getName(),
                    'middlewares' => $middlewares
                ];                
            }
        }
    }

    public function dispatch(): void
    {
        foreach ($this->routes as $route) {

            if ($route['method'] !== strtoupper($this->requestMethod)) continue;

            [$regex, $paramTypes] = RouterParams::buildRegexFromRoute($route['pattern']);

            if (preg_match($regex, $this->requestPath, $matches)) {

                MiddlewareManager::verify($route['middlewares']);
                
                $controller = $this->container->resolve($route['controller']);
                $params = RouterParams::extractTypedParams($matches, $paramTypes);
                call_user_func_array([$controller, $route['action']], [$params]);
                return;
            }
        }

        http_response_code(404);
        echo "404 - Not Found";
    }
}