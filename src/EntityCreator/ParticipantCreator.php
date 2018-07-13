<?php

namespace App\EntityCreator;

use App\Entity\Participant;
use App\Entity\User;

class ParticipantCreator
{

    /**
     * @param array $data
     * @param User $user
     * @return Participant
     */
    public function createByData(array $data, User $user): Participant
    {
        if (!isset($data['amount'], $data['eth_wallet'], $data['yandex_wallet'])) {
            throw new \InvalidArgumentException(sprintf('Invalid data %s', json_encode($data)));
        }

        return (new Participant())
            ->setUser($user)
            ->setAmount($data['amount'])
            ->setEthWallet($data['eth_wallet'])
            ->setYandexWallet($data['yandex_wallet']);
    }
}