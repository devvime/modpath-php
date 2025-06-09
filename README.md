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
use ModPath\Attribute\Prefix;
use ModPath\Attribute\Middleware;
use ModPath\Interface\ControllerInterface;
use ModPath\Middleware\AuthMiddleware;
use ModPath\Middleware\PermissionMiddleware;
use ModPath\Services\UserService;

class UserController implements ControllerInterface
{
    // Optional: Define a route prefix and middleware for all routes in the controller
    #[Prefix(path: '/user', middleware: AuthMiddleware::class)]
    public function __construct(
        private UserService $userService
    ) {}

    #[Route(path: '', method: 'GET')]
    public function index($request, $response): void
    {
        $users = ['Alice', 'Bob', 'Charlie'];
        View::render('users', ['users' => $users]);
    }

    #[Route(path: '/{id:int}', method: 'GET')]
    #[Middleware(PermissionMiddleware::class)]
    public function show($request, $response): void
    {
        $user = $this->userService->getUserInfo($request->params['id']);
        View::render('user', ['id' => $request->params['id'], 'name' => $user]);
    }

    #[Route(path: '', method: 'POST')]
    #[Middleware(PermissionMiddleware::class)]
    public function store($request, $response): void
    {
        echo "Storing new user...";
    }

    #[Route(path: '/{id}', method: 'PUT')]
    #[Middleware(PermissionMiddleware::class)]
    public function update($request, $response): void
    {
        echo "Updating user #{$request->params['id']}...";
    }

    #[Route(path: '/{id}', method: 'DELETE')]
    #[Middleware(PermissionMiddleware::class)]
    public function destroy($request, $response): void
    {
        echo "Deleting user #{$request->params['id']}...";
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
    $response->render('Hello World!'); // Render plain text or HTML

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
    <endloop>
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
<else>
    <p>Values are different</p>
<endif>
```

---

### Summary of Template Syntax

| Feature     | Custom Syntax Example                   | PHP Equivalent                                                 |
| ----------- | --------------------------------------- | -------------------------------------------------------------- |
| Display     | `{{ $name }}`                           | `<?= htmlspecialchars($name) ?>`                               |
| If          | `<if($a > $b)> ... <endif>`             | `<?php if ($a > $b): ?> ... <?php endif; ?>`                   |
| Elseif/Else | `<elseif(...)> ... <else> ...`          | `<?php elseif (...) ?> ... <?php else: ?>`                     |
| Loop        | `<loop($items as $item)> ... <endloop>` | `<?php foreach ($items as $item): ?> ... <?php endforeach; ?>` |
| For        | `<for($x = 0; $x <= 10; $x++)> ... <endfor>` | `<?php for ($x = 0; $x <= 10; $x++): ?> ... <?php endfor; ?>` |

---
