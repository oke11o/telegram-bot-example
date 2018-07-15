<?php

namespace App\Telegram;

use App\Entity\User;
use App\Manager\TelegramUserManager;
use App\Telegram\State\TelegramStateInterface;
use App\Telegram\Type\ReplyMessage;
use App\Telegram\UpdateHandler\StartHandler;
use App\Telegram\UpdateHandler\TelegramUpdateHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use TelegramBot\Api\Types\Update;

class TelegramHandler implements ServiceSubscriberInterface
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
     * @var HandlerResolver
     */
    private $resolver;

    /**
     * TelegramHandler constructor.
     * @param TelegramUserManager $userManager
     * @param AdapterInterface $cache
     * @param HandlerResolver $resolver
     */
    public function __construct(
        TelegramUserManager $userManager,
        AdapterInterface $cache,
        HandlerResolver $resolver
    ) {
        $this->userManager = $userManager;
        $this->cache = $cache;
        $this->resolver = $resolver;
    }

    /**
     * @param Update $update
     * @return ReplyMessage
     */
    public function handleUpdate(Update $update): ReplyMessage
    {
        $user = $this->userManager->receiveUser($update->getMessage());
        $state = $this->currentUserState($user);

        $updateHandler = $this->resolveUpdateHandler($update, $user, $state);

        $response = $updateHandler->handle($update, $user, $state);

        if ($response->isResetState()) {
            $this->clearState($user);
        } elseif ($response->getState()) {
            $this->saveState($user, $response->getState());
        }

        return $response->getMessage();

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
    private function resolveUpdateHandler(Update $update, User $user, $state = null): TelegramUpdateHandlerInterface
    {
        return $this->resolver->resolve($update, $user, $state);
    }

    /**
     * @param User $user
     * @return null|TelegramStateInterface
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function currentUserState(User $user): ?TelegramStateInterface
    {
        $key = $this->getCacheKey($user);

        if ($this->cache->hasItem($key)) {
            return $this->cache->getItem($key)->get();
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

    /**
     * @param User $user
     * @param TelegramStateInterface|null $state
     */
    private function saveState(User $user, TelegramStateInterface $state = null): void
    {
        if (!$state) {
            return;
        }

        $key = $this->getCacheKey($user);
        $item = $this->cache->getItem($key);
        if (!$item->isHit()) {
            $item->set($state);
            $this->cache->save($item);
        }
    }

    /**
     * @param User $user
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function clearState(User $user)
    {
        $key = $this->getCacheKey($user);

        $this->cache->deleteItem($key);
    }

    /**
     * @param User $user
     * @return string
     */
    private function getCacheKey(User $user): string
    {
        return sprintf('current_user_state_%d', $user->getId());
    }
}