<?php

namespace Forgeon;

class Response
{
    public function status(int $code): void
    {
        http_response_code($code);
    }

    public function json(array $data): void
    {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    public function render(string $value): void
    {
        echo $value;
    }
}