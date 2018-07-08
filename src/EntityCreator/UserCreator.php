<?php

namespace App\EntityCreator;

use App\Entity\User;

class UserCreator
{
    /**
     * @param \TelegramBot\Api\Types\User $user
     * @return User
     */
    public function createByTelegramUser(\TelegramBot\Api\Types\User $user): User
    {
        $locale = $user->getLanguageCode() ?? 'en';

        return (new User())
            ->setUsername($user->getUsername() ?? 'telegram'.$user->getId())
            ->setTelegramId($user->getId())
            ->setTelegramUsername($user->getUsername() ?? '')
            ->setFirstName($user->getFirstName() ?? '')
            ->setLastName($user->getLastName() ?? '')
            ->setIsTelegramBot($user->isBot())
            ->setLocale($locale);
    }
}