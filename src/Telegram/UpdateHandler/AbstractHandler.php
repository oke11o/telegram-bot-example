<?php

namespace App\Telegram\UpdateHandler;

use App\Entity\User;
use Symfony\Component\Translation\TranslatorInterface;

abstract class AbstractHandler
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getCommandName(): string
    {
        $commandClass = explode('\\', static::class);
        $command = array_pop($commandClass);

        return '/' . strtolower(str_replace('Handler', '', $command));
    }

    /**
     * @param User $user
     * @return array
     */
    protected function getStdButtons(User $user): array
    {
        return [
            [
                [
                    'text' => $this->translator->trans('buy_eth', [], null, $user->getRealLocale()),
                ],
                [
                    'text' => $this->translator->trans('sell_eth', [], null, $user->getRealLocale()),
                ],
            ],
            [
                [
                    'text' => $this->translator->trans('my_orders', [], null, $user->getRealLocale()),
                ],
            ],
            [
                [
                    'text' => $this->translator->trans('about', [], null, $user->getRealLocale()),
                ],
                [
                    'text' => $this->translator->trans('change_locale', [], null, $user->getRealLocale()),
                ],
            ],
        ];
    }
}