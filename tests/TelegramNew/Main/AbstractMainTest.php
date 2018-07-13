<?php

namespace App\Tests\TelegramNew\Main;

use App\Entity\User;
use App\Repository\UserRepository;
use App\TelegramNew\Controller\CancelController;
use App\TelegramNew\Controller\ChangeLocaleController;
use App\TelegramNew\Controller\CreateParticipantController;
use App\TelegramNew\Main;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractMainTest extends KernelTestCase
{
    protected const TELEGRAM_USER_ID = 121212121;
    protected const FIRST_USER_ID = 2;
    protected const CHAT_ID = 120500956;

    /**
     * @var Main
     */
    protected $main;
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function setUp()
    {
        self::bootKernel();
        $container = self::$container;

        $this->main = $container->get(Main::class);
        $this->em = $container->get(EntityManagerInterface::class);
        $this->userRepository = $this->em->getRepository(User::class);
    }


    protected function createSimpleUpdateWithText($text)
    {
        return [
            'update_id' => 866640363,
            'message' =>
                [
                    'message_id' => 296,
                    'from' =>
                        [
                            'id' => self::TELEGRAM_USER_ID,
                            'is_bot' => false,
                            'first_name' => 'Sergey',
                            'last_name' => 'Bevzenko',
                            'username' => 'oke11o',
                            'language_code' => 'ru-RU',
                        ],
                    'chat' =>
                        [
                            'id' => self::CHAT_ID,
                            'first_name' => 'Sergey',
                            'last_name' => 'Bevzenko',
                            'username' => 'oke11o',
                            'type' => 'private',
                        ],
                    'date' => 1531140851,
                    'text' => $text,
                ],
        ];
    }

    /**
     * @return array
     */
    protected function defaultButtons(): array
    {
        return [
            [
                ['text' => ChangeLocaleController::COMMAND_NAME],
                ['text' => CreateParticipantController::COMMAND_NAME],
                ['text' => CancelController::COMMAND_NAME],
            ],
        ];
    }

    /**
     * @param $container
     */
    protected function cacheClear(ContainerInterface $container): void
    {
        $cache = $container->get(AdapterInterface::class);
        $keys = [
            'current_user_state_'.self::FIRST_USER_ID, //TODO: hardcode. Need fix with fixtures
        ];
        foreach ($keys as $key) {
            if ($cache->hasItem($key)) {
                $cache->deleteItem($key);
            }
        }
    }
}