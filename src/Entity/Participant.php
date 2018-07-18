<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantRepository")
 * @ORM\Table(
 *     name="participant"
 * )
 */
class Participant
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="participants")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $yandex_wallet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $eth_wallet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $amount;

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getYandexWallet(): ?string
    {
        return $this->yandex_wallet;
    }

    public function setYandexWallet(string $yandex_wallet): self
    {
        $this->yandex_wallet = $yandex_wallet;

        return $this;
    }

    public function getEthWallet(): ?string
    {
        return $this->eth_wallet;
    }

    public function setEthWallet(string $eth_wallet): self
    {
        $this->eth_wallet = $eth_wallet;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
