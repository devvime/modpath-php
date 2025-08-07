<?php

namespace ModPath\Http;

use ModPath\View\View;

class Response
{
    public function status(int $code): void
    {
        http_response_code($code);
    }

    public function json(array $data): void
    {
        $this->header('Content-type', 'application/json; charset=utf-8');
        echo json_encode($data);
    }

    public function render(string $file, array $data = []): void
    {
        View::setBasePath($_ENV['VIEWS_DIR']);
        View::render($file, $data);
    }

    public function send(string $value): void
    {
        echo $value;
    }

    public function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    public function header(string $key, string $value): void
    {
        header("{$key}: {$value}");
    }
}