<?php

namespace ModPath\DTO;

use ModPath\Helpers\Message;
use ModPath\DTO\Required;

class IsEmail
{
    public function __construct(
        string $name,
        string | null $email
    ) {
        new Required($name, $email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return Message::send(400, "{$name} is not valid email!");
        }
    }
}
