<?php

namespace App\Manager;

use App\Entity\User;
use App\EntityCreator\UserCreator;
use App\Repository\UserRepository;

class UserManager
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var UserCreator
     */
    private $creator;

    public function __construct(UserRepository $repository, UserCreator $creator)
    {
        $this->repository = $repository;
        $this->creator = $creator;
    }

    /**
     * @param int $telegramId
     * @return User|null
     */
    public function receiveUserByTelegramId(int $telegramId): ?User
    {
        return $this->repository->findByTelegramId($telegramId);
    }

    public function createUserByTelegramUser(\TelegramBot\Api\Types\User $telegramUser): ?User
    {
        return $this->creator->createByTelegramUser($telegramUser);
    }
}