<?php

namespace App\Tests\Telegram;

use App\Telegram\TelegramHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TelegramBot\Api\Types\Update;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class TelegramHandlerTest extends KernelTestCase
{
    /**
     * @var TelegramHandler
     */
    private $handler;

    public function setUp()
    {
        self::bootKernel();
        $container = self::$container;

        $this->handler = $container->get(TelegramHandler::class);

        $cache = $container->get(AdapterInterface::class);
        $keys = [
            'current_user_state_1', //TODO: hardcode. Need fix with fixtures
        ];
        foreach ($keys as $key) {
            if ($cache->hasItem($key)) {
                $cache->deleteItem($key);
            }
        }
    }

    /**
     * @test
     */
    public function changeLocaleChain()
    {
        $chain = $this->createChangeLocaleChain();
        $update = $this->createSimpleUpdate();

        $message = $this->handler->handleUpdate($update);

        $this->assertEquals($message->getText(), 'asdf');
    }


    private function createSimpleUpdate()
    {
        return Update::fromResponse(
            [
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
                        'text' => 'Change language',
                    ],
            ]
        );
    }

    private function createChangeLocaleChain()
    {
        return [
            [
                'request' => [
                    'text' => 'start',
                    'callback' => null,
                ],
                'response' => [
                    'text' => 'hello',
                    'buttons' => [
                        'change_locale',
                        'cancel',
                    ],
                    'buttonsType' => 'simple'
                ],
            ],
            [
                'request' => [
                    'text' => 'change_locale',
                    'callback' => null,
                ],
                'response' => [
                    'text' => 'please_choose_locale',
                    'buttons' => [
                        'en',
                        'ru',
                    ],
                    'buttonsType' => 'simple'
                ],
            ],
            [
                'request' => [
                    'text' => 'change_locale',
                    'callback' => null,
                ],
                'response' => [
                    'text' => 'invalid_locale',
                    'buttons' => [
                        'en',
                        'ru',
                    ],
                    'buttonsType' => 'simple'
                ],
            ],
            [
                'request' => [
                    'text' => 'change_locale',
                    'callback' => null,
                ],
                'response' => [
                    'text' => 'invalid_locale',
                    'buttons' => [
                        'en',
                        'ru',
                    ],
                    'buttonsType' => 'simple'
                ],
            ],
        ];
    }
}
