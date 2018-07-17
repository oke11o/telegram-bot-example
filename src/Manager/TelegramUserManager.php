<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;

class TelegramUserManager
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var \App\Manager\UserManager
     */
    private $userManager;

    public function __construct(EntityManagerInterface $em, UserManager $userManager)
    {
        $this->em = $em;
        $this->userManager = $userManager;
    }

    /**
     * @param \TelegramBot\Api\Types\User $from
     * @param int $chatId
     * @return User $user
     */
    public function receiveUser(\TelegramBot\Api\Types\User $from, int $chatId): User
    {
        $telegramUser = $from;
        $user = $this->userManager->receiveUserByTelegramId($telegramUser->getId());
        if (!$user) {
            $user = $this->userManager->createUserByTelegramUser($telegramUser);
        }

        if ($chatId !== $user->getLastTelegramChatId()) {
            $user->addTelegramChatId($chatId);
        }

        if (!$user->getId()) {
            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }
}