<?php

namespace ModPath\Helpers;

use Medoo\Medoo;

class Database
{
    private $db;

    public function __construct()
    {
        $this->db = new Medoo([
            'database_type' => $_ENV['DATABASE_TYPE'],
            'database_name' => $_ENV['DATABASE_NAME'],
            'server' => $_ENV['DATABASE_SERVER'],
            'username' => $_ENV['DATABASE_USER'],
            'password' => $_ENV['DATABASE_PASSWORD'],
            'port' => $_ENV['DATABASE_PORT'],
            'charset' => 'utf8mb4'
        ]);
    }

    public function connect()
    {
        return $this->db;
    }
}
