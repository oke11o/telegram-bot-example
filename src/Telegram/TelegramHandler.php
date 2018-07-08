<?php

namespace App\Telegram;

use App\Entity\User;
use App\Manager\TelegramUserManager;
use App\Telegram\Type\ReplyMessage;
use App\Telegram\UpdateHandler\StartHandler;
use App\Telegram\UpdateHandler\TelegramUpdateHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use TelegramBot\Api\Types\Update;

class TelegramHandler implements ServiceSubscriberInterface
{
    /**
     * @var TelegramUserManager
     */
    private $userManager;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var \Symfony\Component\Cache\Adapter\FilesystemAdapter
     */
    private $cache;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var HandlerResolver
     */
    private $resolver;

    /**
     * TelegramHandler constructor.
     * @param TelegramUserManager $userManager
     * @param EntityManagerInterface $em
     * @param \Symfony\Component\Cache\Adapter\AdapterInterface $cache
     */
    public function __construct(
        TelegramUserManager $userManager,
        EntityManagerInterface $em,
        ContainerInterface $container,
        \Symfony\Component\Cache\Adapter\AdapterInterface $cache,
        HandlerResolver $resolver
    ) {
        $this->userManager = $userManager;
        $this->em = $em;
        $this->cache = $cache;
        $this->container = $container;
        $this->resolver = $resolver;
    }

    /**
     * @param Update $update
     * @return ReplyMessage
     */
    public function handleUpdate(Update $update): ReplyMessage
    {
        $user = $this->userManager->receiveUser($update);
        $state = $this->currentUserState($user);

        $updateHandler = $this->updateHandlerResolve($update, $user, $state);

        return $updateHandler->handle($update, $user, $state);

    }

    /**
     * @param Update $update
     * @param User $user
     * @param null $state
     * @return TelegramUpdateHandlerInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function updateHandlerResolve(Update $update, User $user, $state = null): TelegramUpdateHandlerInterface
    {
        return $this->resolver->resolve($update, $user, $state);
    }

    /**
     * @param User $user
     * @return mixed|null|\Symfony\Component\Cache\CacheItem
     */
    private function currentUserState(User $user)
    {
        $key = sprintf('current_user_state_%d', $user->getId());
        if ($this->cache->hasItem($key)) {
            return $this->cache->getItem($key);
        }

        return null;
    }


    /**
     * @return array The required service types, optionally keyed by service names
     */
    public static function getSubscribedServices()
    {
        return [
            StartHandler::class,
        ];
    }
}