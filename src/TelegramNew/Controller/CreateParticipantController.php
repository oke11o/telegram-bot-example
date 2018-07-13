<?php

namespace App\TelegramNew\Controller;

use App\Entity\User;
use App\TelegramNew\Request\Arguments;
use App\TelegramNew\Response\ClearReplyMessage;
use App\TelegramNew\Response\Response;
use App\TelegramNew\State\State;
use App\TelegramNew\StateFactory;

class CreateParticipantController implements TelegramControllerInterface
{
    public const COMMAND_NAME = 'create.participant';
    /**
     * @var StateFactory
     */
    private $stateFactory;

    public function __construct(StateFactory $stateFactory)
    {
        $this->stateFactory = $stateFactory;
    }

    public function index(Arguments $arguments, State $state, User $user): Response
    {
        $message = new ClearReplyMessage($arguments->getChatId(), 'text.your.ym.wallet', $this->getOneCancelButtons());

        $newState = $this->stateFactory->create(self::COMMAND_NAME);

        return new Response($message, $newState);
    }


    private function getOneCancelButtons()
    {
        return [
            [
                ['text' => CancelController::COMMAND_NAME],
            ],
        ];
    }
}