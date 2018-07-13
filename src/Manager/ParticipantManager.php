<?php

namespace App\Manager;

use App\Entity\Participant;
use App\Entity\User;
use App\EntityCreator\ParticipantCreator;
use Doctrine\ORM\EntityManagerInterface;

class ParticipantManager
{
    /**
     * @var ParticipantCreator
     */
    private $creator;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ParticipantCreator $creator, EntityManagerInterface $em)
    {
        $this->creator = $creator;
        $this->em = $em;
    }

    /**
     * @param array $data
     * @param User $user
     * @return Participant
     */
    public function createParticipant(array $data, User $user): Participant
    {
        $participant = $this->creator->createByData($data, $user);
        $this->em->persist($participant);

        return $participant;
    }
}