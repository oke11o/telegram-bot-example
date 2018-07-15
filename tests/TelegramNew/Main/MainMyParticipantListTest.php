<?php

namespace App\Tests\TelegramNew\Main;

use TelegramBot\Api\Types\Update;

class MainMyParticipantListTest extends AbstractMainTest
{
    public function setUp()
    {
        parent::setUp();

        $container = self::$container;
        $this->cacheClear($container);
    }

    /**
     * @test
     */
    public function getMyParticipant()
    {
        foreach ($this->getChain() as $key => $testExample) {
            $request = $testExample['request'];
            $response = $testExample['response'];

            $text = $request['text'];

            $update = Update::fromResponse($this->createSimpleUpdateWithText($text));

            $message = $this->main->run($update);

            $this->assertEquals($response, $message->jsonSerialize(), $key);
        }
    }


    public function getChain()
    {
        return [
            'Start message' => [
                'request' => [
                    'text' => 'start',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'hello',
                    'buttons' => $this->defaultButtons(),
                    'buttonType' => 'simple',
                ],
            ],
            'Show participant' => [
                'request' => [
                    'text' => 'my.participant',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'your.my.participant',
                    'buttons' => [
                        [
                            [
                                'text' => 'text 1',
                                'callback_data' => 'callback data 1',
                            ],
                        ],
                        [
                            [
                                'text' => 'text 1',
                                'callback_data' => 'callback data 2',
                            ],
                        ],
                        [
                            [
                                'text' => 'prev',
                                'callback_data' => 'prev',
                            ],
                            [
                                'text' => 'next',
                                'callback_data' => 'next',
                            ],
                        ],
                    ],
                    'buttonType' => 'inline',
                ],
            ],
        ];
    }
}