<?php

namespace App\Telegram\State;

class State implements \JsonSerializable
{
    /**
     * @var string
     */
    private $commandName;
    /**
     * @var string
     */
    private $action;
    /**
     * @var array
     */
    private $data;

    public function __construct(string $commandName, string $action, array $data = [])
    {
        $this->commandName = $commandName;
        $this->action = $action;
        $this->data = $data;
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

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'controller' => $this->getCommandName(),
            'action' => $this->getAction(),
            'data' => $this->getData(),
        ];
    }
}