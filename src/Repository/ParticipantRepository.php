<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    /**
     * @param User $user
     * @return Participant[]
     */
    public function findByUser(User $user)
    {
        return $this->findBy(['user' => $user]);
    }

    /**
     * @param int $id
     * @param User $user
     * @return Participant
     */
    public function findOneByUser(int $id, User $user)
    {
        return $this->findOneBy(['id' => $id, 'user' => $user]);
    }

//    /**
//     * @return Participant[] Returns an array of Participant objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Participant
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
