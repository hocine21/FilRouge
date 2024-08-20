<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * @param array $etats
     * @return Commande[]
     */
    public function findByEtats(array $etats): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.Etat IN (:etats)')
            ->setParameter('etats', $etats)
            ->getQuery()
            ->getResult();
    }
}
