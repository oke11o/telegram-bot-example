<?php

namespace App\TelegramNew\Controller;

use App\Entity\User;
use App\TelegramNew\Request\Arguments;
use App\TelegramNew\Response\ClearReplyMessage;
use App\TelegramNew\Response\Response;
use App\TelegramNew\State\State;
use App\TelegramNew\StateFactory;

class ChangeLocaleController implements TelegramControllerInterface
{
    public const COMMAND_NAME = 'change.locale';
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
        $message = new ClearReplyMessage($arguments->getChatId(), self::COMMAND_NAME, $this->getButtons());

        $newState = $this->stateFactory->create(self::COMMAND_NAME);

        return new Response($message, $newState);
    }

    public function chooseLocale(Arguments $arguments, State $state, User $user)
    {
        $text = $arguments->getText();
        if (\in_array($text, ['en', 'ru'])) { //validation
            $user->setLocale($text);

            $text = 'success.change.locale';
            $message = new ClearReplyMessage($arguments->getChatId(), $text, HomeController::getDefaultButtons());
            $newState = $this->stateFactory->create(HomeController::COMMAND_NAME);

            return new Response($message, $newState);
        }

        $validationErrorMessage = 'invalid.locale';
        $message = new ClearReplyMessage($arguments->getChatId(), $validationErrorMessage, $this->getButtons());
        $newState = $this->stateFactory->create(self::COMMAND_NAME);

        return new Response($message, $newState);
    }

    /**
     * @return array
     */
    protected function getButtons(): array
    {
        return [
            [
                [
                    'text' => 'en',
                ],
                [
                    'text' => 'ru',
                ],
                [
                    'text' => CancelController::COMMAND_NAME,
                ],
            ],
        ];
    }
}