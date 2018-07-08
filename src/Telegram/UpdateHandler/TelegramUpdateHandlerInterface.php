<?php

namespace App\Telegram\UpdateHandler;

use App\Entity\User;
use App\Telegram\State\TelegramStateInterface;
use App\Telegram\Type\HandleResponse;
use App\Telegram\Type\ReplyMessage;
use TelegramBot\Api\Types\Update;

interface TelegramUpdateHandlerInterface
{
    public function handle(Update $update, User $user, TelegramStateInterface $state = null): HandleResponse;

    public static function getCommandName(): string;
}