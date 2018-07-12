<?php

namespace App\TelegramNew;

class StateFactory
{
    public function create(string $name)
    {
        return new State($name);
    }
}