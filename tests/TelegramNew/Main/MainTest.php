<?php

namespace App\Tests\TelegramNew\Main;

use App\Entity\User;
use App\TelegramNew\Controller\CancelController;
use TelegramBot\Api\Types\Update;

class MainTest extends AbstractMainTest
{
    private static $testCount = 0;

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

        $this->assertEquals($response, $message->jsonSerialize());

        if ($checkLocale) {
            $user = $this->userRepository->findByTelegramId(self::TELEGRAM_USER_ID);
            $this->assertInstanceOf(User::class, $user);
            $this->assertEquals($text, $user->getRealLocale());
        }
    }

    public function createChangeLocaleChain()
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
                    'text' => 'change.locale',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'change.locale',
                    'buttons' => $this->getThisButtons(),
                ],
            ],
            [
                'request' => [
                    'text' => 'invalid.locale',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'invalid.locale',
                    'buttons' => $this->getThisButtons(),
                ],
            ],
            [
                'request' => [
                    'text' => 'en',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'success.change.locale',
                    'buttons' => $this->defaultButtons(),
                ],
            ],
            [
                'request' => [
                    'text' => 'change.locale',
                ],
                'response' => [
                    'chatId' => self::CHAT_ID,
                    'text' => 'change.locale',
                    'buttons' => $this->getThisButtons(),
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
        ];
    }

    /**
     * @return array
     */
    private function getThisButtons(): array
    {
        return [
            [
                ['text' => 'en'],
                ['text' => 'ru'],
                ['text' => CancelController::COMMAND_NAME],
            ],
        ];
    }
}