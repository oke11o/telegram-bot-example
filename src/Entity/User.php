<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(
 *     name="user",
 *     indexes={
 *          @ORM\Index(name="idx_user_username", columns={"username"}),
 *          @ORM\Index(name="idx_user_telegram_id", columns={"telegram_id"})
 *     }
 * )
 */
class User
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $telegramId = 0;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telegramUsername = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName = '';

    /**
     * @ORM\Column(type="simple_array")
     */
    private $telegramChatIds = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $locale = '';

    /**
     * @ORM\Column(type="boolean")
     */
    private $isTelegramBot = false;

    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getTelegramId(): int
    {
        return $this->telegramId;
    }

    public function setTelegramId(int $telegramId): self
    {
        $this->telegramId = $telegramId;

        return $this;
    }

    public function getTelegramUsername(): string
    {
        return $this->telegramUsername;
    }

    public function setTelegramUsername(string $telegramUsername): self
    {
        $this->telegramUsername = $telegramUsername;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getTelegramChatIds(): array
    {
        return $this->telegramChatIds;
    }

    public function setTelegramChatIds(array $telegramChatIds): self
    {
        $this->telegramChatIds = $telegramChatIds;

        return $this;
    }

    public function addTelegramChatId(int $chatId): self
    {
        if (\in_array($chatId, $this->telegramChatIds, true)) {
            $new = [];
            foreach ($this->telegramChatIds as $id) {
                if ($id !== $chatId) {
                    $new[] = $id;
                }
            }
            $this->telegramChatIds = $new;
        }
        $this->telegramChatIds[] = $chatId;

        return $this;
    }

    public function getLastTelegramChatId(): int
    {
        if (empty($this->telegramChatIds)) {
            return 0;
        }

        return $this->telegramChatIds[\count($this->telegramChatIds) - 1];
    }


    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getIsTelegramBot(): bool
    {
        return $this->isTelegramBot;
    }

    public function setIsTelegramBot(bool $isTelegramBot): self
    {
        $this->isTelegramBot = $isTelegramBot;

        return $this;
    }
}
