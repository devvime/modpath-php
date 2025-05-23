<?php

namespace Mini;

use ReflectionClass;
use Mini\Request;
use Mini\Response;
use Mini\Container;
use Mini\RouterParams;
use Mini\MiddlewareManager;
use Mini\Attribute\Route;
use Mini\Attribute\Middleware;
use Mini\Attribute\Prefix;

class Router
{
    private array $routes = [];
    private string $requestPath;
    private string $requestMethod;
    private Container $container;
    private array $routePrefix;

    public function __construct()
    {
        $this->requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
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
                    prefixMiddleware: $this->routePrefix['middleware']
                );

                $this->routes[] = [
                    'pattern' => $this->routePrefix['path'] . $route->path,
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

        http_response_code(404);
        echo "404 - Not Found";
    }
}