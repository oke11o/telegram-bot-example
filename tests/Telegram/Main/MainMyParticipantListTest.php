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
                                'text' => 'ID: 1, Amount: 101, User: oke11o',
                                'callback_data' => '1',
                            ],
                        ],
                        [
                            [
                                'text' => 'ID: 2, Amount: 102, User: oke11o',
                                'callback_data' => '2',
                            ],
                        ],
                        [
                            [
                                'text' => 'ID: 3, Amount: 103, User: oke11o',
                                'callback_data' => '3',
                            ],
                        ],
                        [
                            [
                                'text' => 'ID: 4, Amount: 104, User: oke11o',
                                'callback_data' => '4',
                            ],
                        ],
                        [
                            [
                                'text' => 'ID: 5, Amount: 105, User: oke11o',
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
                                'text' => 'ID: 6, Amount: 106, User: oke11o',
                                'callback_data' => '6',
                            ],
                        ],
                        [
                            [
                                'text' => 'ID: 7, Amount: 107, User: oke11o',
                                'callback_data' => '7',
                            ],
                        ],
                        [
                            [
                                'text' => 'ID: 8, Amount: 108, User: oke11o',
                                'callback_data' => '8',
                            ],
                        ],
                        [
                            [
                                'text' => 'ID: 9, Amount: 109, User: oke11o',
                                'callback_data' => '9',
                            ],
                        ],
                        [
                            [
                                'text' => 'ID: 10, Amount: 110, User: oke11o',
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
                    'text' => 'your.my.participant',
                    'buttons' => [
                        [
                            [
                                'text' => 'text 3',
                                'callback_data' => 'callback data 1',
                            ],
                        ],
                        [
                            [
                                'text' => 'text 4',
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