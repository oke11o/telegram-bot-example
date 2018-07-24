<?php

namespace App\Tests\Telegram\Main;

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
            if (isset($request['data'])) {
                $data = $request['data'];
                $update = Update::fromResponse($this->createCallbackQuerUpdateWithText($text, $data));
            } else {
                $update = Update::fromResponse($this->createSimpleUpdateWithText($text));
            }

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
            'Show participant 1' => [
                'request' => [
                    'text' => 'my.participant',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'your.my.participant',
                    'buttons' => [
                        [
                            [
                                'text' => sprintf('ID: 1, Amount: %f, User: oke11o', 1.3),
                                'callback_data' => '1',
                            ],
                        ],
                        [
                            [
                                'text' => sprintf('ID: 2, Amount: %f, User: oke11o', 1.3),
                                'callback_data' => '2',
                            ],
                        ],
                        [
                            [
                                'text' => sprintf('ID: 3, Amount: %f, User: oke11o', 1.3),
                                'callback_data' => '3',
                            ],
                        ],
                        [
                            [
                                'text' => sprintf('ID: 4, Amount: %f, User: oke11o', 1.3),
                                'callback_data' => '4',
                            ],
                        ],
                        [
                            [
                                'text' => sprintf('ID: 5, Amount: %f, User: oke11o', 1.3),
                                'callback_data' => '5',
                            ],
                        ],
                        [
                            [
                                'text' => 'To end',
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
            'Show participant 2' => [
                'request' => [
                    'text' => 'my.participant',
                    'data' => 'next',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'your.my.participant',
                    'buttons' => [
                        [
                            [
                                'text' => sprintf('ID: 6, Amount: %f, User: oke11o', 1.3),
                                'callback_data' => '6',
                            ],
                        ],
                        [
                            [
                                'text' => sprintf('ID: 7, Amount: %f, User: oke11o', 1.3),
                                'callback_data' => '7',
                            ],
                        ],
                        [
                            [
                                'text' => sprintf('ID: 8, Amount: %f, User: oke11o', 1.3),
                                'callback_data' => '8',
                            ],
                        ],
                        [
                            [
                                'text' => sprintf('ID: 9, Amount: %f, User: oke11o', 1.3),
                                'callback_data' => '9',
                            ],
                        ],
                        [
                            [
                                'text' => sprintf('ID: 10, Amount: %f, User: oke11o', 1.3),
                                'callback_data' => '10',
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
            'Show participant show' => [
                'request' => [
                    'text' => 'my.participant',
                    'data' => '2',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'your.participant.id.2
Amount: 1, Eth: 0x123451234512345123451234512345',
                    'buttons' => [
                        [
                            [
                                'text' => 'list',
                            ],
                        ],
                        [
                            [
                                'text' => 'main',
                            ],
                        ],
                    ],
                    'buttonType' => 'simple',
                ],
            ],
            'back to start message' => [
                'request' => [
                    'text' => 'main',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'cancel',
                    'buttons' => $this->defaultButtons(),
                    'buttonType' => 'simple',
                ],
            ],
        ];
    }
}