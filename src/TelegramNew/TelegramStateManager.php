<?php

namespace App\TelegramNew;

use App\Entity\User;
use App\TelegramNew\State\State;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class TelegramStateManager
{

    /**
     * @var AdapterInterface
     */
    private $cache;
    /**
     * @var StateFactory
     */
    private $factory;

    public function __construct(AdapterInterface $cache, StateFactory $factory)
    {
        $this->cache = $cache;
        $this->factory = $factory;
    }

    /**
     * @param User $user
     * @return State|mixed
     */
    public function getState(User $user)
    {
        $key = $this->getCacheKey($user);

        if ($this->cache->hasItem($key)) {
            return $this->cache->getItem($key)->get();
        }

        return $this->factory->create('init');
    }

    public function saveState(State $state, User $user): void
    {
        $key = $this->getCacheKey($user);
        $item = $this->cache->getItem($key);
        $item->set($state);
        $this->cache->save($item);
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