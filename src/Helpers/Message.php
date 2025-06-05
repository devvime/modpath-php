<?php

namespace ModPath\Helpers;

use ModPath\Http\Response;

class Message
{
    public static function send(
        int $status,
        string $message
    ) {
        $response = new Response();
        $response->status($status);
        $response->json([
            "status" => $status,
            "message" => $message
        ]);
        return;
    }
}
