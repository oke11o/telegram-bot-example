<?php

namespace App\Telegram;

use TelegramBot\Api\BotApi;

class TelegramBotApi
{
    /**
     * @var BotApi
     */
    private $bot;

    public function __construct(string $telegramBotApiToken)
    {
        $this->bot = new BotApi($telegramBotApiToken);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param int $timeout
     * @return \TelegramBot\Api\Types\Update[]
     *
     * @throws \TelegramBot\Api\Exception
     * @throws \TelegramBot\Api\InvalidArgumentException
     */
    public function getUpdates($offset = 0, $limit = 100, $timeout = 0): array
    {
        return $this->bot->getUpdates($offset, $limit, $timeout);
    }
}