<?php

namespace App\Tests\Telegram\Main;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Telegram\Controller\CancelController;
use App\Telegram\Controller\ChangeLocaleController;
use App\Telegram\Controller\CreateParticipantController;
use App\Telegram\Controller\MyParticipantController;
use App\Telegram\Main;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractMainTest extends KernelTestCase
{
    protected const TELEGRAM_USER_ID = 121212121;
    protected const FIRST_USER_ID = 1;
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

        parent::setUp();
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

    protected function createCallbackQuerUpdateWithText($text, $data)
    {
        return [

            'update_id' => 866640391,
            'callback_query' =>
                [

                    'id' => '517547666663007318',
                    'from' =>
                        [
                            'id' => self::TELEGRAM_USER_ID,
                            'is_bot' => false,
                            'first_name' => 'Sergey',
                            'last_name' => 'Bevzenko',
                            'username' => 'oke11o',
                            'language_code' => 'ru-RU',
                        ],
                    'message' =>
                        [
                            'message_id' => 340,
                            'from' =>
                                [
                                    'id' => 531111111,
                                    'is_bot' => true,
                                    'first_name' => 'Sb_lsd_tmp_bot',
                                    'username' => 'Sb_lsd_tmp_bot',
                                ],
                            'chat' =>
                                [
                                    'id' => self::CHAT_ID,
                                    'first_name' => 'Sergey',
                                    'last_name' => 'Bevzenko',
                                    'username' => 'oke11o',
                                    'type' => 'private',
                                ],
                            'date' => 1531834453,
                            'text' => $text,
                        ],
                    'chat_instance' => '8268603998804215931',
                    'data' => $data,
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
                ['text' => MyParticipantController::COMMAND_NAME],
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
        for ($i = 1; $i < 100; $i++) {
            $keys = [
                'current_user_state_'.$i, //TODO: hardcode. Need fix with fixtures
            ];
            foreach ($keys as $key) {
                if ($cache->hasItem($key)) {
                    $cache->deleteItem($key);
                }
            }
        }
    }
}