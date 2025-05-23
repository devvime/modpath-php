<?php

namespace Mini\Controllers;

use Mini\View;
use Mini\Attribute\Route;
use Mini\Attribute\Prefix;
use Mini\Attribute\Middleware;
use Mini\Interface\ControllerInterface;
use Mini\Middleware\AuthMiddleware;
use Mini\Services\ProductService;

class ProductController implements ControllerInterface
{
    #[Prefix(path: '/product')]
    public function __construct(
        private ProductService $productService
    ) {}

    #[Route(path: '', method: 'GET')]
    public function index($request, $response): void
    {
        $products = ['Notebook', 'Teclado', 'PS5'];
        View::render('products', ['products' => $products]);
    }

    #[Route(path: '/{id:int}', method: 'GET')]
    #[Middleware(AuthMiddleware::class)]
    public function show($request, $response): void
    {
        $product = $this->productService->getProductInfo($request->params['id']);
        View::render('product', ['id' => $request->params['id'], 'name' => $product]);
    }

    #[Route(path: '', method: 'POST')]
    public function store($request, $response): void
    {
        echo "Storing new product...";
    }

    #[Route(path: '/{id}', method: 'PUT')]
    public function update($request, $response): void
    {
        echo "Updating product #{$request->params['id']}...";
    }

    #[Route(path: '/{id}', method: 'DELETE')]
    public function destroy($request, $response): void
    {
        echo "Deleting product #{$request->params['id']}...";
    }
}
