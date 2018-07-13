<?php

namespace App\TelegramNew;

use App\Entity\User;
use App\Manager\TelegramUserManager;
use App\Telegram\State\TelegramStateInterface;
use App\Telegram\Type\ReplyMessage;
use App\Telegram\Type\ReplyMessageFactory;
use App\Telegram\UpdateHandler\StartHandler;
use App\Telegram\UpdateHandler\TelegramUpdateHandlerInterface;
use App\TelegramNew\Request\Request;
use App\TelegramNew\Request\RequestFactory;
use App\TelegramNew\Response\ClearReplyMessage;
use App\TelegramNew\Response\Response;
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
    /**
     * @var TelegramStateManager
     */
    private $telegramStateManager;
    /**
     * @var RequestFactory
     */
    private $requestFactory;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        TelegramUserManager $userManager,
        AdapterInterface $cache,
        ReplyMessageFactory $replyMessageFactory,
        TranslatorInterface $translator,
        TelegramStateManager $telegramStateManager,
        RequestFactory $requestFactory,
        EntityManagerInterface $em
    ) {
        $this->userManager = $userManager;
        $this->cache = $cache;
        $this->replyMessageFactory = $replyMessageFactory;
        $this->translator = $translator;
        $this->telegramStateManager = $telegramStateManager;
        $this->requestFactory = $requestFactory;
        $this->em = $em;
    }

    /**
     * Данный раннер получает Update и возвращает ReplyMessage.
     * В процессе он создает контроллер. То есть надо определить контроллер и экшн. В них должны приходить параметры.
     * По аналогии с HTTP запросом.
     * Тут так же есть сессия.
     * @param Update $update
     * @return ClearReplyMessage
     */
    public function run(Update $update): ClearReplyMessage
    {
        $user = $this->userManager->receiveUser($update);

        // тут надо получить сессию. И проверить не просреченна ли она. Если нет - создать новую.
        $state = $this->telegramStateManager->getState($user);

        /** @var Request $request */
        $request = $this->requestFactory->create($update->getMessage()->getChat()->getId(), $update->getMessage()->getText(), $state);


        /** @var Response $response */
        $response = \call_user_func(
            [$request->getController(), $request->getActionName()],
            $request->getArguments(),
            $state,
            $user
        );

        $this->telegramStateManager->saveState($response->getState(), $user);
        $this->em->flush();

        return $response->getMessage();


//        $buttons = new ReplyKeyboardMarkup(
//            $this->getButtons($user)
//        );
//
//        $chatId = $update->getMessage()->getChat()->getId();
//        $text = 'change_locale';
//
//        return $this->replyMessageFactory->create($chatId, $text, $buttons, $user->getRealLocale());
    }
//
//    public function translateClearReplayMessage(ClearReplyMessage $clearReplyMessage): ReplyMessage
//    {
//        return new ReplyMessage();
//    }

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