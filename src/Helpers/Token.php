<?php

namespace ModPath\Helpers;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token
{
    public static function encode(string | array | object $value)
    {
        return JWT::encode($value, $_ENV['SECRET'], 'HS256');
    }

    public static function decode(string | array | object $value)
    {
        return JWT::decode($value, new Key($_ENV['SECRET'], 'HS256'));
    }

    public static function get()
    {
        try {
            return explode(' ', $_SERVER['HTTP_AUTHORIZATION'])[1];
        } catch (Exception $error) {
            throw new Exception("401 Unauthorized: {$error}");
        }
    }
}
