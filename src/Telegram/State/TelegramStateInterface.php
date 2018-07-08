<?php

namespace App\Telegram\State;

use Psr\Cache\CacheItemInterface;

interface TelegramStateInterface extends \JsonSerializable
{
    public function setHandlerName(string $handlerName);

    public function getHandlerName(): string;

    public function setCacheItem(CacheItemInterface $cacheItem);

    public function getCacheItem(): ?CacheItemInterface;

    public function getStateName(): string;

    public function setNeedClean();

    public function needClean(): bool;

    public function resolveHandlerName(): string;

    public static function fromJson(array $json);

    public static function fromStringJson(string $jsonString);
}