<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AdminQuizTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AdminQuizTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminQuizTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminQuizTable[]    findAll()
 * @method AdminQuizTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminQuizTableRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdminQuizTable::class);
    }

//    /**
//     * @return AdminQuizTable[] Returns an array of AdminQuizTable objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdminQuizTable
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
