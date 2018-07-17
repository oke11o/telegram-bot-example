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

    public function __construct(int $chatId, string $text)
    {
        $this->text = $text;
        $this->chatId = $chatId;
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


}