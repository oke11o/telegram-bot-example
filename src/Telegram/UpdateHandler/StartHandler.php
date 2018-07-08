<?php

namespace App\Telegram\UpdateHandler;

use App\Entity\User;
use App\Telegram\Type\ReplyMessage;
use App\Telegram\Type\ReplyMessageFactory;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\Update;

class StartHandler implements TelegramUpdateHandlerInterface
{
    /**
     * @var ReplyMessageFactory
     */
    private $factory;

    public function __construct(ReplyMessageFactory $factory)
    {
        $this->factory = $factory;
    }

    public function handle(Update $update, User $user): ReplyMessage
    {
        $buttons = [
            [
                [
                    'text' => 'Мои предложения',
                    'callback_data' => '/my',
                ],
                [
                    'text' => 'Купить ETH',
                    'callback_data' => '/buy',
                ],
            ],
            [
                [
                    'text' => 'О сервисе',
                    'callback_data' => '/about',
                ],
            ]
        ];
        $buttons = new ReplyKeyboardMarkup(
            $buttons
        );

        $chatId = $update->getMessage()->getChat()->getId();
        $text = 'start_message';

        return $this->factory->create($chatId, $text, $buttons);
    }
}