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

    public function __construct(string $text, array $buttons)
    {
        $this->text = $text;
        $this->buttons = $buttons;
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
            'text' => $this->getText(),
            'buttons' => $this->getButtons(),
        ];
    }
}