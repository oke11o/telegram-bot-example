<?php

namespace App\Tests\TelegramNew\Main;

use App\Entity\User;
use App\TelegramNew\Controller\CancelController;
use TelegramBot\Api\Types\Update;

class MainCreateTest extends AbstractMainTest
{
    private static $testCount = 0;

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
                    'buttons' => [
                        [
                            ['text' => 'cancel']
                        ],
                    ],
                ],
            ],
        ];
    }
}