# ModPath PHP

### A Minimal and Expressive PHP Micro Routing Framework

---

## 📦 Installation

```bash
composer require devvime/modpath
```

---

## ⚙️ Initial Configuration

```php
require dirname(__DIR__) . '/vendor/autoload.php';

use ModPath\Router\Router;
use ModPath\Controllers\UserController;
use ModPath\Controllers\ProductController;

$router = new Router();

$router->registerRoutes([
    UserController::class,
    ProductController::class,
]);

$router->dispatch();
```

---

## 🧽 Defining a Controller with Routes and Middleware

```php
namespace ModPath\Controllers;

use ModPath\View\View;
use ModPath\Attribute\Route;
use ModPath\Attribute\Controller;
use ModPath\Attribute\Middleware;
use ModPath\Interface\ControllerInterface;
use ModPath\Middleware\AuthMiddleware;
use ModPath\Middleware\PermissionMiddleware;
use ModPath\Services\UserService;

// Optional: Define a route controller and middleware for all routes in the controller
#[Controller(path: '/user', middleware: AuthMiddleware::class)]
class UserController implements ControllerInterface
{
    public function __construct() {}

    #[Route(path: '', method: 'GET')]
    public function index($request, $response): void
    {
        $response->send('Users list');
    }

    #[Route(path: '/{id}', method: 'GET')]
    public function show($request, $response): void
    {
        $response->send("Info for user id: {$request->params['id']}");
    }

    #[Route(path: '', method: 'POST')]
    public function store($request, $response): void
    {
        $response->send("Storing new user");
    }

    #[Route(path: '/{id}', method: 'PUT')]
    public function update($request, $response): void
    {
        $response->send("Updating user id: {$request->params['id']}");
    }

    #[Route(path: '/{id}', method: 'DELETE')]
    public function destroy($request, $response): void
    {
        $response->send("Deleting user id: {$request->params['id']}");
    }
}
```

---

## 📥 Request Parameters

```php
#[Route(path: 'user/{id}', method: 'POST')]
public function store($request, $response): void
{
    $request->params;   // URL parameters (e.g., $request->params['id'])
    $request->body;     // Parsed JSON body (e.g., $request->body->email)
    $request->query;    // Query string parameters (e.g., $_GET['key'])
    $request->headers;  // HTTP headers
}
```

---

## 📤 Response Handling

```php
#[Route(path: 'user/{id}', method: 'POST')]
#[Middleware(PermissionMiddleware::class)]
public function store($request, $response): void
{
    $response->send('Hello World!'); // Render plain text

    $response->render('views/index', ["mesage" => "Hello world!"]); // Render HTML template

    $response->json([
        'status' => 200,
        'message' => 'Hello World!'
    ]); // Return JSON response
}
```

---

## 🛡️ Middleware Structure

All middleware classes must be placed under `ModPath\Middleware` and implement the `MiddlewareInterface`.
The `handle()` method is required and must return a boolean indicating whether to continue the request.

```php
namespace ModPath\Middleware;

use ModPath\Interface\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        // Authentication logic here
        return true;
    }
}
```

---

## 🧹 Template Engine Syntax

<!-- > **Note:** All views must be placed in the `src/Views` directory for the `View::render('file')` function to work correctly. -->

### Display Variables

```php
<h1>User Details</h1>
<p>ID: {{ $id }}</p>       <!-- <?= htmlspecialchars($id) ?> -->
<p>Name: {{ $name }}</p>   <!-- <?= htmlspecialchars($name) ?> -->
```

### Loops

```php
<h1>User List</h1>
<ul>
    <loop($users as $user)>
        <li>{{ $user }}</li>
    </loop>
</ul>
```

Equivalent to:

```php
<?php foreach ($users as $user): ?>
    <li><?= htmlspecialchars($user) ?></li>
<?php endforeach; ?>
```

### Conditions

```php
<if($a == $b)>
    <p>Values are equal</p>
<elseif($a > $b)>
    <p>A is greater than B</p>
<else/>
    <p>Values are different</p>
</if>
```

---

### Summary of Template Syntax

| Feature     | Custom Syntax Example                   | PHP Equivalent                                                 |
| ----------- | --------------------------------------- | -------------------------------------------------------------- |
| Display     | `{{ $name }}`                           | `<?= htmlspecialchars($name) ?>`                               |
| If          | `<if($a > $b)> ... </if>`             | `<?php if ($a > $b): ?> ... <?php endif; ?>`                   |
| Elseif/Else | `<elseif(...)> ... <else/> ...`          | `<?php elseif (...) ?> ... <?php else: ?>`                     |
| Loop        | `<loop($items as $item)> ... </loop>` | `<?php foreach ($items as $item): ?> ... <?php endforeach; ?>` |
| For        | `<for($x = 0; $x <= 10; $x++)> ... </for>` | `<?php for ($x = 0; $x <= 10; $x++): ?> ... <?php endfor; ?>` |

---
