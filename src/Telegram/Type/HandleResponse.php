<?php

namespace App\Telegram\Type;

use App\Telegram\State\TelegramStateInterface;

class HandleResponse
{
    /**
     * @var ReplyMessage
     */
    private $message;
    /**
     * @var TelegramStateInterface
     */
    private $state;
    /**
     * @var bool
     */
    private $resetState = false;

    public function __construct(ReplyMessage $message, TelegramStateInterface $state = null, $resetState = false)
    {
        $this->message = $message;
        $this->state = $state;
        $this->resetState = $resetState;
    }

    /**
     * @return ReplyMessage
     */
    public function getMessage(): ReplyMessage
    {
        return $this->message;
    }

    /**
     * @return TelegramStateInterface
     */
    public function getState(): ?TelegramStateInterface
    {
        return $this->state;
    }

    /**
     * @return bool
     */
    public function isResetState(): bool
    {
        return $this->resetState;
    }
}