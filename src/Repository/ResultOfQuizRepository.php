<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ResultOfQuiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ResultOfQuiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResultOfQuiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResultOfQuiz[]    findAll()
 * @method ResultOfQuiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultOfQuizRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ResultOfQuiz::class);
    }


//    /**
//     * @return ResultOfQuiz[] Returns an array of ResultOfQuiz objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ResultOfQuiz
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
