<?php

namespace Mini;

class View
{
    public static function render(string $template, array $data = [])
    {
        extract($data);
        include dirname(__DIR__) . "/src/views/{$template}.php";
    }
}