<?php

namespace ModPath\Router;

use ReflectionClass;
use ModPath\Http\Request;
use ModPath\Http\Response;
use ModPath\Core\Container;
use ModPath\Router\RouterParams;
use ModPath\Middleware\MiddlewareManager;
use ModPath\Attribute\Route;
use ModPath\Attribute\Middleware;
use ModPath\Attribute\Prefix;

class Router
{
    private array $routes = [];
    private string $requestPath;
    private string $requestMethod;
    private Container $container;
    private array $routePrefix;

    public function __construct()
    {
        $this->requestPath = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->container = new Container();
        $this->routePrefix = [];
    }

    public function registerRoutes(array $controllers)
    {
        foreach ($controllers as $controller) {
            $this->registerRoutesFromController($controller);
        }
    }

    public function registerRoutesFromController(string $controllerClass): void
    {
        $refClass = new ReflectionClass($controllerClass);

        foreach ($refClass->getMethods() as $method) {

            foreach ($method->getAttributes(Prefix::class) as $prefix_attr) {
                $route_prefix = $prefix_attr->newInstance();
                $this->routePrefix['path'] = $route_prefix->path;
                $this->routePrefix['middleware'] = $route_prefix->middleware;
            }

            foreach ($method->getAttributes(Route::class) as $attr) {

                $route = $attr->newInstance();
                $middlewares = MiddlewareManager::getMiddlewares(
                    middlewares: $method->getAttributes(Middleware::class), 
                    prefixMiddleware: isset($this->routePrefix['middleware']) ? $this->routePrefix['middleware'] : null
                );

                $this->routes[] = [
                    'pattern' => isset($this->routePrefix['path']) ? $this->routePrefix['path'] : '' . $route->path,
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

                $request = new Request($params);
                $response = new Response();

                call_user_func_array([$controller, $route['action']], [$request, $response]);
                return;
            }
        }

        if (
            $_SERVER['REQUEST_URI'] !== '/404'
            && str_contains($_SERVER['REQUEST_URI'], '/api/')
        ) {
            header('Location: /404');
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