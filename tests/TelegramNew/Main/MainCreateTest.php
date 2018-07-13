<?php

namespace App\Tests\TelegramNew\Main;

use TelegramBot\Api\Types\Update;

class MainCreateTest extends AbstractMainTest
{
    private static $testCount = 0;
    private static $needPurgeClear = true;

    public function setUp()
    {
        parent::setUp();
        $container = self::$container;

        if (self::$needPurgeClear) {
            self::$needPurgeClear = false;

            $this->cacheClear($container);
        }
    }


    /**
     * @test
     * @dataProvider createExamples
     */
    public function create($request, $response)
    {
        $this::$testCount++;

        $text = $request['text'];

        $update = Update::fromResponse($this->createSimpleUpdateWithText($text));

        $message = $this->main->run($update);

        $this->assertEquals($response, $message->jsonSerialize());

    }

    public function createExamples()
    {
        return [
            [
                'request' => [
                    'text' => 'start',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'hello',
                    'buttons' => $this->defaultButtons(),
                ],
            ],
            [
                'request' => [
                    'text' => 'create.participant',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'text.your.ym.wallet',
                    'buttons' => $this->getOnlyCancelButton(),
                ],
            ],
            [
                'request' => [
                    'text' => 'cancel',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'cancel',
                    'buttons' => $this->defaultButtons(),
                ],
            ],
            [
                'request' => [
                    'text' => 'create.participant',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'text.your.ym.wallet',
                    'buttons' => $this->getOnlyCancelButton(),
                ],
            ],
            [
                'request' => [
                    'text' => 'huejsdfs',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'validation.text.your.ym.wallet',
                    'buttons' => $this->getOnlyCancelButton(),
                ],
            ],
            [
                'request' => [
                    'text' => '4324234342',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'validation.text.your.ym.wallet',
                    'buttons' => $this->getOnlyCancelButton(),
                ],
            ],
            [
                'request' => [
                    'text' => '11123311123311123311123333333333',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'text.your.eth.wallet',
                    'buttons' => $this->getOnlyCancelButton(),
                ],
            ],
            [
                'request' => [
                    'text' => 'cancel',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'cancel',
                    'buttons' => $this->defaultButtons(),
                ],
            ],
            [
                'request' => [
                    'text' => 'create.participant',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'text.your.ym.wallet',
                    'buttons' => $this->getOnlyCancelButton(),
                ],
            ],
            [
                'request' => [
                    'text' => '11123311123311123311123333333333',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'text.your.eth.wallet',
                    'buttons' => $this->getOnlyCancelButton(),
                ],
            ],
            [
                'request' => [
                    'text' => 'dsafsdfasfdasfasf',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'validate.your.eth.wallet',
                    'buttons' => $this->getOnlyCancelButton(),
                ],
            ],
            [
                'request' => [
                    'text' => '0x123451234512345123451234512345',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'text.amount',
                    'buttons' => $this->getOnlyCancelButton(),
                ],
            ],
            [
                'request' => [
                    'text' => 'validate.amount',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'validate.amount',
                    'buttons' => $this->getOnlyCancelButton(),
                ],
            ],
            [
                'request' => [
                    'text' => '1.3',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'please.agree',
                    'buttons' => [
                        [
                            ['text' => 'yes'],
                            ['text' => 'no'],
                        ]
                    ],
                ],
            ],
            [
                'request' => [
                    'text' => 'yes',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'success.create.participant.1', //TODO: need check ID
                    'buttons' => $this->defaultButtons(),
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    private function getOnlyCancelButton(): array
    {
        return [
            [
                ['text' => 'cancel']
            ],
        ];
    }
}