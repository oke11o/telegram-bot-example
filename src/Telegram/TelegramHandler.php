<?php

namespace App\Telegram;

use App\Entity\User;
use App\Manager\TelegramUserManager;
use Doctrine\ORM\EntityManagerInterface;
use TelegramBot\Api\Types\Update;

class TelegramHandler
{
    /**
     * @var TelegramUserManager
     */
    private $userManager;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(TelegramUserManager $userManager, EntityManagerInterface $em)
    {
        $this->userManager = $userManager;
        $this->em = $em;
    }

    public function handleUpdate(Update $update)
    {
        $user = $this->userManager->receiveUser($update);

        $a = $update;
    }


}