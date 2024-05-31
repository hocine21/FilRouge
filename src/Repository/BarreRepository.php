<?php

namespace App\Repository;

use App\Entity\Barre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Barre>
 *
 * @method Barre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Barre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Barre[]    findAll()
 * @method Barre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BarreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Barre::class);
    }

    //    /**
    //     * @return Barre[] Returns an array of Barre objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Barre
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
