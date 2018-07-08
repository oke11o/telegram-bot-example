<?php

namespace App\Telegram\Type;

use \TelegramBot\Api\Types\ReplyKeyboardMarkup;
use \TelegramBot\Api\Types\ReplyKeyboardHide;
use \TelegramBot\Api\Types\ForceReply;
use \TelegramBot\Api\Types\ReplyKeyboardRemove;

class ReplyMessage
{
    /**
     * @var int
     */
    private $chatId;
    /**
     * @var string
     */
    private $text;
    /**
     * @var null|string
     */
    private $parseMode;
    /**
     * @var bool
     */
    private $disablePreview;
    /**
     * @var int|null
     */
    private $replyToMessageId;
    /**
     * @var null|ForceReply|ReplyKeyboardHide|ReplyKeyboardMarkup|ReplyKeyboardRemove
     */
    private $replyMarkup;
    /**
     * @var bool
     */
    private $disableNotification;

    /**
     * @param int $chatId
     * @param string $text
     * @param string|null $parseMode
     * @param bool $disablePreview
     * @param int|null $replyToMessageId
     * @param ReplyKeyboardMarkup|ReplyKeyboardHide|ForceReply|ReplyKeyboardRemove|null $replyMarkup
     * @param bool $disableNotification
     */
    public function __construct(
        int $chatId,
        string $text,
        string $parseMode = null,
        $disablePreview = false,
        int $replyToMessageId = null,
        $replyMarkup = null,
        $disableNotification = false
    ) {
        $this->chatId = $chatId;
        $this->text = $text;
        $this->parseMode = $parseMode;
        $this->disablePreview = $disablePreview;
        $this->replyToMessageId = $replyToMessageId;
        $this->replyMarkup = $replyMarkup;
        $this->disableNotification = $disableNotification;
    }

    /**
     * @return int
     */
    public function getChatId(): int
    {
        return $this->chatId;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return null|string
     */
    public function getParseMode(): ?string
    {
        return $this->parseMode;
    }

    /**
     * @return bool
     */
    public function isDisablePreview(): bool
    {
        return $this->disablePreview;
    }

    /**
     * @return int|null
     */
    public function getReplyToMessageId(): ?int
    {
        return $this->replyToMessageId;
    }

    /**
     * @return null|ForceReply|ReplyKeyboardHide|ReplyKeyboardMarkup|ReplyKeyboardRemove
     */
    public function getReplyMarkup()
    {
        return $this->replyMarkup;
    }

    /**
     * @return bool
     */
    public function isDisableNotification(): bool
    {
        return $this->disableNotification;
    }
}