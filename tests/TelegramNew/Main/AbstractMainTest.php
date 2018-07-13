<?php

namespace App\Tests\TelegramNew\Main;

use App\Entity\User;
use App\Repository\UserRepository;
use App\TelegramNew\Main;
use App\TelegramNew\Response\ClearReplyMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Cache\Adapter\AdapterInterface;

abstract class AbstractMainTest extends KernelTestCase
{
    protected const FIRST_USER_ID = 1;

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

    protected static $needPurgeClear = true;

    public function setUp()
    {
        self::bootKernel();
        $container = self::$container;

        $this->main = $container->get(Main::class);
        $this->em = $container->get(EntityManagerInterface::class);
        $this->userRepository = $this->em->getRepository(User::class);

        if (self::$needPurgeClear) {
            self::$needPurgeClear = false;

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


    protected function createSimpleUpdateWithText($text)
    {
        return [
            'update_id' => 866640363,
            'message' =>
                [
                    'message_id' => 296,
                    'from' =>
                        [
                            'id' => 120500956,
                            'is_bot' => false,
                            'first_name' => 'Sergey',
                            'last_name' => 'Bevzenko',
                            'username' => 'oke11o',
                            'language_code' => 'ru-RU',
                        ],
                    'chat' =>
                        [
                            'id' => 120500956,
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

    protected function prepareResponse(ClearReplyMessage $message)
    {
        $result = [
            'text' => $message->getText(),
        ];

        $result['buttons'] = $this->parseResponseKeyboard($message->getButtons());

        return $result;
    }

    private function parseResponseKeyboard(array $rows)
    {
        $result = [];

        foreach ($rows as $row) {
            foreach ($row as $col) {
                $result[] = $col['text'];
            }
        }

        return $result;
    }
}