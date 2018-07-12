<?php

namespace Tests\App\TelegramNew;

use App\Telegram\Type\ReplyMessage;
use App\TelegramNew\Main;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\Update;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MainTest extends KernelTestCase
{

    /**
     * @var Main
     */
    private $main;

    private static $needPurgeClear = true;

    public function setUp()
    {
        self::bootKernel();
        $container = self::$container;

        $this->main = $container->get(Main::class);

        if (self::$needPurgeClear) {
            self::$needPurgeClear = false;

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
    }

    /**
     * @test
     * @dataProvider createChangeLocaleChain
     */
    public function changeLocaleChain($request, $response)
    {
        $update = Update::fromResponse($this->createSimpleUpdateWithText($request['text']));

        $message = $this->main->run($update);

        $this->assertEquals($response, $this->prepareResponse($message));
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
                        'Change language',
                        'Cancel',
                    ],
                    'buttonsType' => 'simple',
                ],
            ],
            [
                'request' => [
                    'text' => 'Change language',
                ],
                'response' => [
                    'text' => 'Please choose locale',
                    'buttons' => [
                        'en',
                        'ru',
                    ],
                    'buttonsType' => 'simple',
                ],
            ],
            [
                'request' => [
                    'text' => 'Change language',
                ],
                'response' => [
                    'text' => 'Invalid locale',
                    'buttons' => [
                        'en',
                        'ru',
                    ],
                    'buttonsType' => 'simple',
                ],
            ],
            [
                'request' => [
                    'text' => 'change_locale',
                ],
                'response' => [
                    'text' => 'invalid_locale',
                    'buttons' => [
                        'en',
                        'ru',
                    ],
                    'buttonsType' => 'simple',
                ],
            ],
        ];
    }

    private function prepareResponse(ReplyMessage $message)
    {
        $result = [
            'text' => $message->getText(),
        ];

        $markup = $message->getReplyMarkup();
        if ($markup instanceof ReplyKeyboardMarkup) {

            $result['buttons'] = $this->parseResponseKeyboard($markup);
            $result['buttonsType'] = 'simple';
        }

        return $result;
    }

    private function parseResponseKeyboard(ReplyKeyboardMarkup $markup)
    {
        $result = [];

        foreach ($markup->getKeyboard() as $row) {
            foreach ($row as $col) {
                $result[] = $col['text'];
            }
        }

        return $result;
    }
}