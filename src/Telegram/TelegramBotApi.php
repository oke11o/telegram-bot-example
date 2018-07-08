<?php

namespace App\Telegram;

use App\Telegram\Type\ReplyMessage;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;

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

    /**
     * @param ReplyMessage $replyMessage
     * @return Message
     *
     * @throws \TelegramBot\Api\Exception
     * @throws \TelegramBot\Api\InvalidArgumentException
     */
    public function sendMessage(
        ReplyMessage $replyMessage
    ): Message {

        return $this->bot->sendMessage(
            $replyMessage->getChatId(),
            $replyMessage->getText(),
            $replyMessage->getParseMode(),
            $replyMessage->isDisablePreview(),
            $replyMessage->getReplyToMessageId(),
            $replyMessage->getReplyMarkup(),
            $replyMessage->isDisableNotification()
        );
    }
}