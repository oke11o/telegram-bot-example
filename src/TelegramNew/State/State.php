<?php

namespace App\TelegramNew\State;

class State
{
    /**
     * @var string
     */
    private $commandName;
    /**
     * @var string
     */
    private $action;

    public function __construct(string $commandName, string $action)
    {
        $this->commandName = $commandName;
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getCommandName(): string
    {
        return $this->commandName;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}