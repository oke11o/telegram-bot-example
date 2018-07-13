<?php

namespace App\TelegramNew\Response;

use App\Telegram\Type\ReplyMessage;
use App\TelegramNew\State\State;

class Response
{
    /**
     * @var ClearReplyMessage
     */
    private $message;
    /**
     * @var State
     */
    private $state;

    public function __construct(ClearReplyMessage $message, State $state)
    {
        $this->message = $message;
        $this->state = $state;
    }

    /**
     * @return ClearReplyMessage
     */
    public function getMessage(): ClearReplyMessage
    {
        return $this->message;
    }

    /**
     * @return State
     */
    public function getState(): State
    {
        return $this->state;
    }
}