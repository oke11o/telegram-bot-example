<?php

namespace App\Telegram\State;

use App\Telegram\UpdateHandler\CancelHandler;
use App\Telegram\UpdateHandler\ChangeLocaleHandler;

class ChangeLocaleState extends AbstractTelegramState
{
    public function resolveHandlerName(string $resolved = null): string
    {
        if ($resolved === CancelHandler::class) {
            return $resolved;
        }

        return $this->getHandlerName();
    }

    public static function fromJson(array $json)
    {
        return new static();
    }

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return ChangeLocaleHandler::class;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'className' => $this->getStateName(),
        ];
    }
}