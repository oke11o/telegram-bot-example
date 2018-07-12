<?php

namespace App\TelegramNew;

class State
{
    /**
     * @var string
     */
    private $commandName;

    public function __construct(string $commandName)
    {
        $this->commandName = $commandName;
    }

    /**
     * @return string
     */
    public function getCommandName(): string
    {
        return $this->commandName;
    }


}