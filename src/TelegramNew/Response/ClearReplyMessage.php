<?php

namespace App\TelegramNew\Response;

class ClearReplyMessage implements \JsonSerializable
{
    /**
     * @var string
     */
    private $text;
    /**
     * @var array
     */
    private $buttons;
    /**
     * @var int
     */
    private $chatId;

    public function __construct(int $chatId, string $text, array $buttons)
    {
        $this->chatId = $chatId;
        $this->text = $text;
        $this->buttons = $buttons;
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
     * @return array
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'chatId' => $this->getChatId(),
            'text' => $this->getText(),
            'buttons' => $this->getButtons(),
        ];
    }
}