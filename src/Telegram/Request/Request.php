<?php

namespace App\Telegram\Request;

use App\Telegram\Controller\TelegramControllerInterface;

class Request
{
    /**
     * @var TelegramControllerInterface
     */
    private $controller;
    /**
     * @var string
     */
    private $actionName;
    /**
     * @var Arguments
     */
    private $arguments;

    public function __construct(TelegramControllerInterface $controller, string $actionName, Arguments $arguments)
    {
        $this->controller = $controller;
        $this->actionName = $actionName;
        $this->arguments = $arguments;
    }

    /**
     * @return TelegramControllerInterface
     */
    public function getController(): TelegramControllerInterface
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return $this->actionName;
    }

    /**
     * @return Arguments
     */
    public function getArguments(): Arguments
    {
        return $this->arguments;
    }

}