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

    public function createExamples()
    {
        return [
            [
                'request' => [
                    'text' => 'start',
                ],
                'response' => [
                    'text' => 'hello',
                    'buttons' => $this->defaultButtons(),
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
                    'buttons' => $this->defaultButtons(),
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
                    'buttons' => $this->defaultButtons(),
                ],
            ],
        ];
    }
}