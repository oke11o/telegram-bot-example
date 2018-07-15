<?php

namespace App\TelegramNew\Controller;

use App\Entity\User;
use App\TelegramNew\Request\Arguments;
use App\TelegramNew\Response\ClearReplyMessage;
use App\TelegramNew\Response\Response;
use App\TelegramNew\State\State;
use App\TelegramNew\StateFactory;

class MyParticipantController implements TelegramControllerInterface
{
    public const COMMAND_NAME = 'my.participant';
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
        $message = new ClearReplyMessage(
            $arguments->getChatId(),
            'your.'.self::COMMAND_NAME,
            $this->getButtons(),
            ClearReplyMessage::BUTTON_TYPE_INLINE
        );

        $newState = $this->stateFactory->create(self::COMMAND_NAME);

        return new Response($message, $newState);
    }

    private function getButtons()
    {
        return [
            [
                [
                    'text' => 'text 1',
                    'callback_data' => 'callback data 1',
                ],
            ],
            [
                [
                    'text' => 'text 1',
                    'callback_data' => 'callback data 2',
                ],
            ],
            [
                [
                    'text' => 'prev',
                    'callback_data' => 'prev',
                ],
                [
                    'text' => 'next',
                    'callback_data' => 'next',
                ],
            ],
        ];
    }
}