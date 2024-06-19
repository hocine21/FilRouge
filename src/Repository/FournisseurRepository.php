<?php

namespace App\Repository;

use App\Entity\Fournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FournisseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fournisseur::class);
    }

    /**
     * @return Fournisseur[] Returns an array of Fournisseur objects
     */
    public function findByNomFournisseur(string $term)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.nomFournisseur LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->getQuery()
            ->getResult();
    }
}
