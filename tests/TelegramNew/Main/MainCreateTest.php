<?php

namespace App\Tests\TelegramNew\Main;

use App\Entity\User;
use App\Repository\UserRepository;
use App\TelegramNew\Controller\CancelController;
use App\TelegramNew\Main;
use App\TelegramNew\Response\ClearReplyMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TelegramBot\Api\Types\Update;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MainCreateTest extends KernelTestCase
{
    private const FIRST_USER_ID = 1;

    /**
     * @var Main
     */
    private $main;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserRepository
     */
    private $userRepository;

    private static $needPurgeClear = true;
    private static $testCount = 0;

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

    /**
     * @test
     * @dataProvider createChangeLocaleChain
     */
    public function changeLocaleChain($request, $response)
    {
        $this::$testCount++;
        $checkLocale = false;

        $text = $request['text'];
        if ($text === 'en') {
            $text = ['ru', 'en'][\random_int(0,1)];
            $checkLocale = true;
        }

        $update = Update::fromResponse($this->createSimpleUpdateWithText($text));

        $message = $this->main->run($update);

        $this->assertEquals($response, $this->prepareResponse($message));

        if ($checkLocale) {
            $user = $this->userRepository->find(self::FIRST_USER_ID);
            $this->assertInstanceOf(User::class, $user);
            $this->assertEquals($text, $user->getRealLocale());
        }
    }


    private function createSimpleUpdateWithText($text)
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

    public function createChangeLocaleChain()
    {
        return [
            [
                'request' => [
                    'text' => 'start',
                ],
                'response' => [
                    'text' => 'hello',
                    'buttons' => [
                        'change_locale',
                        CancelController::COMMAND_NAME,
                    ],
                ],
            ],
            [
                'request' => [
                    'text' => 'change_locale',
                ],
                'response' => [
                    'text' => 'change_locale',
                    'buttons' => [
                        'en',
                        'ru',
                        CancelController::COMMAND_NAME,
                    ],
                ],
            ],
            [
                'request' => [
                    'text' => 'invalid_locale',
                ],
                'response' => [
                    'text' => 'invalid_locale',
                    'buttons' => [
                        'en',
                        'ru',
                        CancelController::COMMAND_NAME,
                    ],
                ],
            ],
            [
                'request' => [
                    'text' => 'en',
                ],
                'response' => [
                    'text' => 'success_change_locale',
                    'buttons' => [
                        'change_locale',
                        CancelController::COMMAND_NAME,
                    ],
                ],
            ],
            [
                'request' => [
                    'text' => 'change_locale',
                ],
                'response' => [
                    'text' => 'change_locale',
                    'buttons' => [
                        'en',
                        'ru',
                        CancelController::COMMAND_NAME,
                    ],
                ],
            ],
            [
                'request' => [
                    'text' => 'cancel',
                ],
                'response' => [
                    'text' => 'cancel',
                    'buttons' => [
                        'change_locale',
                        CancelController::COMMAND_NAME,
                    ],
                ],
            ],
        ];
    }

    private function prepareResponse(ClearReplyMessage $message)
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