<?php

namespace ModPath\Helpers;

use Error;

class View
{
    public static function getFile(string $name): string
    {
        try {
            $file = file_get_contents($_ENV['VIEWS_DIR'] . "/{$name}.php");
            return $file;
        } catch (\Exception $err) {
            throw new Error($err);
        }
    }

    public static function render(string $name, array $data = [])
    {
        $m = new \Mustache\Engine(['entity_flags' => ENT_QUOTES]);
        echo $m->render(self::getFile($name), $data);
    }

    public static function get(string $name, array $data = [])
    {
        $m = new \Mustache\Engine(['entity_flags' => ENT_QUOTES]);
        return $m->render(self::getFile($name), $data);
    }
}