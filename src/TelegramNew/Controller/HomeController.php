<?php

namespace App\TelegramNew\Controller;

use App\Entity\User;
use App\TelegramNew\Request\Arguments;
use App\TelegramNew\Response\ClearReplyMessage;
use App\TelegramNew\Response\Response;
use App\TelegramNew\State;

class HomeController implements TelegramControllerInterface
{
    public const COMMAND_NAME = 'hello';

    /**
     * @param Arguments $arguments
     * @param State $state
     * @param User $user
     * @return Response
     */
    public function index(Arguments $arguments, State $state, User $user): Response
    {
        $message = new ClearReplyMessage(self::COMMAND_NAME, self::getDefaultButtons());

        return new Response($message, $state);
    }

    /**
     * @return array
     */
    public static function getDefaultButtons(): array
    {
        return [
            [
                [
                    'text' => ChangeLocaleController::COMMAND_NAME,
                ],
                [
                    'text' => CancelController::COMMAND_NAME,
                ],
            ],
        ];
    }


    /**
     * @return array
     */
    protected function getStdButtons(): array
    {
        return [
            [
                [
                    'text' => 'buy_eth',
                ],
                [
                    'text' => 'sell_eth',
                ],
            ],
            [
                [
                    'text' => 'my_orders',
                ],
            ],
            [
                [
                    'text' => 'about',
                ],
                [
                    'text' => 'change_locale',
                ],
            ],
        ];
    }
}