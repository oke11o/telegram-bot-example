<?php

namespace App\TelegramNew;

use App\Entity\User;
use App\Manager\TelegramUserManager;
use App\Telegram\State\TelegramStateInterface;
use App\Telegram\Type\ReplyMessage;
use App\Telegram\Type\ReplyMessageFactory;
use App\Telegram\UpdateHandler\StartHandler;
use App\Telegram\UpdateHandler\TelegramUpdateHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use Symfony\Component\Translation\TranslatorInterface;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\Update;


class Main
{
    /**
     * @var TelegramUserManager
     */
    private $userManager;
    /**
     * @var AdapterInterface
     */
    private $cache;
    /**
     * @var ReplyMessageFactory
     */
    private $replyMessageFactory;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        TelegramUserManager $userManager,
        AdapterInterface $cache,
        ReplyMessageFactory $replyMessageFactory,
        TranslatorInterface $translator
    ) {
        $this->userManager = $userManager;
        $this->cache = $cache;
        $this->replyMessageFactory = $replyMessageFactory;
        $this->translator = $translator;
    }

    /**
     * @param Update $update
     * @return ReplyMessage
     */
    public function run(Update $update): ReplyMessage
    {
        $user = $this->userManager->receiveUser($update);

        $buttons = new ReplyKeyboardMarkup(
            $this->getButtons($user)
        );

        $chatId = $update->getMessage()->getChat()->getId();
        $text = 'change_locale';

        return $this->replyMessageFactory->create($chatId, $text, $buttons, $user->getRealLocale());
    }

    /**
     * @param User $user
     * @return array
     */
    protected function getButtons(User $user): array
    {
        return [
            [
                [
                    'text' => 'EN',
                ],
                [
                    'text' => 'RU',
                ],
            ],
            [
                [
                    'text' => $this->translator->trans('cancel', [], null, $user->getRealLocale()),
                ],
            ],
        ];
    }
}