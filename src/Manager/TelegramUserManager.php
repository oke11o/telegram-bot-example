<?php

namespace App\Manager;

use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param Update $update
     * @return User $user
     */
    public function receiveUser(Update $update): User
    {
        $telegramUser = $update->getMessage()->getFrom();
        $user = $this->userManager->receiveUserByTelegramId($telegramUser->getId());
        if (!$user) {
            $user = $this->userManager->createUserByTelegramUser($telegramUser);
        }

        $chatId = $update->getMessage()->getChat()->getId();
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