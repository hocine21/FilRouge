<?php
// EntrepotRepository.php

namespace App\Repository;

use App\Entity\Entrepot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EntrepotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entrepot::class);
    }

    public function findAllDistinctCodesPostaux()
    {
        return $this->createQueryBuilder('e')
            ->select('e.codePostale')
            ->distinct()
            ->getQuery()
            ->getResult();
    }
}

