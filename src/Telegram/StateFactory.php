<?php

namespace App\Telegram;

use App\Telegram\State\State;

class StateFactory
{
    public function create(string $name, string $action = 'index', array $data = [])
    {
        return new State($name, $action, $data);
    }
}