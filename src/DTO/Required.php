<?php

namespace Forgeon\DTO;

use Forgeon\Helpers\Message;

class Required
{
    public function __construct(
        string $input_name,
        string | null $input_value
    ) {
        if (
            $input_value === null ||
            $input_value === ''
        ) {
            return Message::send(status: 400, message: "{$input_name} is required!");
        }
    }
}
