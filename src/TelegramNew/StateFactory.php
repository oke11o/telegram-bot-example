<?php

namespace App\TelegramNew;

use App\TelegramNew\State\State;

class StateFactory
{
    public function create(string $name, string $action = 'index')
    {
        return new State($name, $action);
    }
}