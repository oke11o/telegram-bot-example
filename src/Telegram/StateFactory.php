<?php

namespace App\Telegram;

use App\Telegram\State\State;

class StateFactory
{
    public function create(string $name, string $action = 'index', $data = [])
    {
        return new State($name, $action, $data);
    }
}