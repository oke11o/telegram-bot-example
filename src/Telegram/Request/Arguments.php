<?php

namespace App\Telegram\Request;

class Arguments
{
    /**
     * @var string
     */
    private $text;
    /**
     * @var int
     */
    private $chatId;
    /**
     * @var string
     */
    private $callbackData;

    public function __construct(int $chatId, string $text, string $callbackData = null)
    {
        $this->text = $text;
        $this->chatId = $chatId;
        $this->callbackData = $callbackData;
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
     * @return string
     */
    public function getCallbackData(): ?string
    {
        return $this->callbackData;
    }
}