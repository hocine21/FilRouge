<?php

namespace App\Repository;

use App\Entity\EntrepotStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EntrepotStock>
 *
 * @method EntrepotStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntrepotStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntrepotStock[]    findAll()
 * @method EntrepotStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntrepotStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntrepotStock::class);
    }

    //    /**
    //     * @return EntrepotStock[] Returns an array of EntrepotStock objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?EntrepotStock
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
