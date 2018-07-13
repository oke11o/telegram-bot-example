<?php

namespace App\TelegramNew;

use App\TelegramNew\State\State;

class StateFactory
{
    public function create(string $name)
    {
        return new State($name);
    }

    public function createParticipantState()
    {
        return new ParticipantState();
    }
}