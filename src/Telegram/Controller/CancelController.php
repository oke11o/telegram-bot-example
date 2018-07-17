<?php

namespace App\Telegram\Controller;

use App\Entity\User;
use App\Telegram\Request\Arguments;
use App\Telegram\Response\ClearReplyMessage;
use App\Telegram\Response\Response;
use App\Telegram\State\State;
use App\Telegram\StateFactory;

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
        $message = new ClearReplyMessage($arguments->getChatId(), self::COMMAND_NAME, HomeController::getDefaultButtons());

        $newState = $this->stateFactory->create(HomeController::COMMAND_NAME);

        return new Response($message, $newState);
    }

}