<?php

namespace App\Repository;

use App\Entity\ProduitFournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProduitFournisseur>
 *
 * @method ProduitFournisseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitFournisseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitFournisseur[]    findAll()
 * @method ProduitFournisseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitFournisseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitFournisseur::class);
    }

    // Exemple de méthode de requête personnalisée
    /*
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    */

    // Exemple de méthode de requête pour récupérer un seul résultat
    /*
    public function findOneBySomeField($value): ?ProduitFournisseur
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
    */
}