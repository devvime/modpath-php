<?php

namespace ModPath\DTO;

use ModPath\Helpers\Message;

class NotAllow
{
    public function __construct(
        string $input_name,
        string | null $input_value
    ) {
        if (isset($input_value) && $input_value !== null) {
            return Message::send(400, "{$input_name} is not valid!");
        }
    }
}
