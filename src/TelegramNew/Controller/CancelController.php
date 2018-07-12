<?php

namespace App\TelegramNew\Controller;

use App\Entity\User;
use App\TelegramNew\Request\Arguments;
use App\TelegramNew\Response\ClearReplyMessage;
use App\TelegramNew\Response\Response;
use App\TelegramNew\State;
use App\TelegramNew\StateFactory;

class CancelController implements TelegramControllerInterface
{
    public const COMMAND_NAME = 'cancel';
    /**
     * @var StateFactory
     */
    private $stateFactory;

    public function __construct(StateFactory $stateFactory)
    {
        $this->stateFactory = $stateFactory;
    }

    public function cancel(Arguments $arguments, State $state, User $user): Response
    {
        $message = new ClearReplyMessage(self::COMMAND_NAME, HomeController::getDefaultButtons());

        $newState = $this->stateFactory->create(HomeController::COMMAND_NAME);

        return new Response($message, $newState);
    }

}