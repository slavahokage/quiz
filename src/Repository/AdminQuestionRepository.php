<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AdminQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AdminQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminQuestion[]    findAll()
 * @method AdminQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminQuestionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdminQuestion::class);
    }

//    /**
//     * @return AdminQuestion[] Returns an array of AdminQuestion objects
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
    public function findOneBySomeField($value): ?AdminQuestion
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
