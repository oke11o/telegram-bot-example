<?php

namespace App\Telegram\Response;

class ClearReplyMessage implements \JsonSerializable
{
    public const BUTTON_TYPE_SIMPLE = 'simple';
    public const BUTTON_TYPE_INLINE = 'inline';

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
    /**
     * @var string
     */
    private $buttonType;

    public function __construct(int $chatId, string $text, array $buttons, string $buttonType = self::BUTTON_TYPE_SIMPLE)
    {
        $this->chatId = $chatId;
        $this->text = $text;
        $this->buttons = $buttons;
        $this->buttonType = $buttonType;
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
     * @return string
     */
    public function getButtonType(): string
    {
        return $this->buttonType;
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
            'buttonType' => $this->getButtonType(),
        ];
    }
}