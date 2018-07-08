<?php

namespace App\Telegram\State;

use Psr\Cache\CacheItemInterface;

abstract class AbstractTelegramState implements TelegramStateInterface
{
    /**
     * @var string
     */
    private $handlerName;
    /**
     * @var CacheItemInterface|null
     */
    private $cacheItem;
    /**
     * @var boolean
     */
    private $clean = false;

    public function setHandlerName(string $handlerName)
    {
        $this->handlerName = $handlerName;
    }

    public function getHandlerName(): string
    {
        return $this->handlerName;
    }

    public function setCacheItem(CacheItemInterface $cacheItem)
    {
        $this->cacheItem = $cacheItem;
    }

    public function getCacheItem(): ?CacheItemInterface
    {
        return $this->cacheItem;
    }


    public static function fromStringJson(string $jsonString)
    {
        $data = json_decode($jsonString, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(sprintf('Invalid MQ [%s] message', __CLASS__));
        }

        return static::fromJson($data);
    }

    public function getStateName(): string
    {
        return \get_class($this);
    }

    public function setNeedClean()
    {
        $this->clean = true;

        return $this;
    }

    public function needClean(): bool
    {
        return $this->clean;
    }
}