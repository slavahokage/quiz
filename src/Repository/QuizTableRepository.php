<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\QuizTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QuizTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizTable[]    findAll()
 * @method QuizTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizTableRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QuizTable::class);
    }

    public function getTest(): array
    {

        return $this->findBy(array(), array('score' => 'DESC'));
    }

//    /**
//     * @return QuizTable[] Returns an array of QuizTable objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuizTable
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
