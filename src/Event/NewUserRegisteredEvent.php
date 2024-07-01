<?php

namespace App\Event;

use App\DTO\NewUserDTO;

class NewUserRegisteredEvent
{
    public function __construct(public readonly NewUserDTO $data)
    {
    }
}
